<?php

use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Evaluation;
use App\Models\Homework;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Notification::fake();
    Cache::flush();
});

it('returns homework index for course student', function () {
    $user = User::factory()->create();
    $teacher = Teacher::factory()->create();
    $course = Course::factory()->create(['teacher_id' => $teacher->id]);
    $courseStudent = CourseStudent::factory()->create([
        'user_id' => $user->id,
        'course_id' => $course->id
    ]);

    Homework::factory()->count(3)->create(['course_student_id' => $courseStudent->id]);

    $response = $this->actingAs($user)
        ->get(route('homework.index', $course->id));

    $response->assertStatus(200);
    $response->assertViewIs('homework.index');
    $response->assertViewHas('homeworks');
    $response->assertViewHas('courseId', $course->id);
});

it('shows homework details', function () {
    $user = User::factory()->create();
    $teacher = Teacher::factory()->create();
    $course = Course::factory()->create(['teacher_id' => $teacher->id]);
    $courseStudent = CourseStudent::factory()->create([
        'user_id' => $user->id,
        'course_id' => $course->id
    ]);
    $homework = Homework::factory()->create(['course_student_id' => $courseStudent->id]);
    Evaluation::factory()->create(['homework_id' => $homework->id, 'value' => 5]);

    $response = $this->actingAs($user)
        ->get(route('homework.show', $homework->id));

    $response->assertStatus(200);
    $response->assertViewIs('homework.show');
    $response->assertViewHas('homework');
    $response->assertViewHas('highestScore', 5);
});

it('validates homework store request', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('homework.store', 1), []);

    $response->assertSessionHasErrors(['title', 'body']);
});

it('prevents student from evaluating homework', function () {
    $user = User::factory()->create();
    $homework = Homework::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('homework.evaluate', $homework->id), ['evaluation' => 4]);

    $response->assertStatus(403);
});

it('prevents student from searching homeworks', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('homework.search', ['search' => 'test']));

    $response->assertStatus(403);
});

it('clears search cache when new homework is created', function () {
    Cache::put('homework_search_keys', ['test_key_1', 'test_key_2']);
    Cache::put('test_key_1', 'cached_data');
    Cache::put('test_key_2', 'more_cached_data');

    $user = User::factory()->create();
    $teacher = Teacher::factory()->create();
    $course = Course::factory()->create(['teacher_id' => $teacher->id]);
    CourseStudent::factory()->create([
        'user_id' => $user->id,
        'course_id' => $course->id
    ]);

    $homeworkData = [
        'title' => 'Test Homework',
        'body' => 'Test homework body content that is long enough'
    ];

    $this->actingAs($user)
        ->post(route('homework.store', $course->id), $homeworkData);

    $this->assertNull(Cache::get('test_key_1'));
    $this->assertNull(Cache::get('test_key_2'));
    $this->assertNull(Cache::get('homework_search_keys'));
});