<?php

use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('returns all courses for regular user', function () {
    $user = User::factory()->create();
    Course::factory()->count(5)->create();

    $response = $this->actingAs($user)
        ->get(route('course.index'));

    $response->assertStatus(200);
    $response->assertViewIs('course.index');
    $response->assertViewHas('courses');
    $response->assertViewHas('user');
});


it('prevents non-teacher from storing course', function () {
    $user = User::factory()->create();

    $courseData = [
        'module_name' => 'Test Course',
        'start_date' => '2024-02-01'
    ];

    $response = $this->actingAs($user)
        ->post(route('course.store'), $courseData);

    $response->assertStatus(403);
});

it('shows course details', function () {
    $user = User::factory()->create();
    $teacher = Teacher::factory()->create();
    $course = Course::factory()->create(['teacher_id' => $teacher->id]);

    $response = $this->actingAs($user)
        ->get(route('course.show', $course->id));

    $response->assertStatus(200);
    $response->assertViewIs('course.show');
    $response->assertViewHas('course');
    $response->assertViewHas('user');
    $response->assertViewHas('edit', false);
});


it('prevents non-owner from updating course', function () {
    $teacherUser = User::factory()->create();
    $otherTeacher = Teacher::factory()->create();
    $course = Course::factory()->create(['teacher_id' => $otherTeacher->id]);

    $updateData = [
        'module_name' => 'Updated Course Name',
        'start_date' => '2024-03-01'
    ];

    $response = $this->actingAs($teacherUser)
        ->put(route('course.update', $course->id), $updateData);

    $response->assertStatus(403);
});

it('prevents non-teacher from updating course', function () {
    $user = User::factory()->create();
    $teacher = Teacher::factory()->create();
    $course = Course::factory()->create(['teacher_id' => $teacher->id]);

    $updateData = [
        'module_name' => 'Updated Course Name',
        'start_date' => '2024-03-01'
    ];

    $response = $this->actingAs($user)
        ->put(route('course.update', $course->id), $updateData);

    $response->assertStatus(403);
});

it('prevents non-owner from deleting course', function () {
    $teacherUser = User::factory()->create();
    $otherTeacher = Teacher::factory()->create();
    $course = Course::factory()->create(['teacher_id' => $otherTeacher->id]);

    $response = $this->actingAs($teacherUser)
        ->delete(route('course.destroy', $course->id));

    $response->assertStatus(403);
    $this->assertDatabaseHas('courses', ['id' => $course->id]);
});

it('shows user enrolled courses', function () {
    $user = User::factory()->create();
    $teacher = Teacher::factory()->create();
    $courses = Course::factory()->count(3)->create(['teacher_id' => $teacher->id]);
    
    foreach ($courses as $course) {
        CourseStudent::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id
        ]);
    }

    $response = $this->actingAs($user)
        ->get(route('courses.my_courses'));

    $response->assertStatus(200);
    $response->assertViewIs('course.index');
    $response->assertViewHas('courses');
    $response->assertViewHas('user');
});

it('shows available courses to pick', function () {
    $user = User::factory()->create();
    $teacher = Teacher::factory()->create();
    $enrolledCourse = Course::factory()->create(['teacher_id' => $teacher->id]);
    $availableCourse = Course::factory()->create(['teacher_id' => $teacher->id]);
    
    CourseStudent::factory()->create([
        'user_id' => $user->id,
        'course_id' => $enrolledCourse->id
    ]);

    $response = $this->actingAs($user)
        ->get(route('course.pick'));

    $response->assertStatus(200);
    $response->assertViewIs('course.pick');
    $response->assertViewHas('courses');
    
    $courses = $response->viewData('courses');
    $this->assertEquals(1, $courses->total());
    $this->assertEquals($availableCourse->id, $courses->first()->id);
});

