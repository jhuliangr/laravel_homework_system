<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index']);

Route::get('/dashboard', function () {
    $user = Auth::user();
    $teacher = $user->teacher;
    return view('userzone.dashboard', compact('teacher', 'user'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'is_teacher'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('course', \App\Http\Controllers\CourseController::class);
    Route::get('/my_courses', [App\Http\Controllers\CourseController::class, 'user_courses'])->name('courses.my_courses');
    Route::get('/pick_courses', [App\Http\Controllers\CourseController::class, 'pick'])->name('course.pick');
    Route::post('/enroll_in/{course_id}', [App\Http\Controllers\CourseController::class, 'enroll_in'])->name('course.enroll_in');
    
});

require __DIR__ . '/auth.php';
