<?php

use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Homework;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('prevents non-teacher from accessing students index', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('student.enrolled', $course->id));

    $response->assertStatus(404);
});

it('prevents non-teacher from accessing student details', function () {
    $user = User::factory()->create();
    $student = User::factory()->create();
    $course = Course::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('student.show', ['courseId' => $course->id, 'id' => $student->id]));

    $response->assertStatus(404);
});