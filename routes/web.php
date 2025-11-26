<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index'])->name('home');
Route::get('/back', [App\Http\Controllers\BackController::class, 'back'])->name('back.clean');

Route::get('/dashboard', function () {
    $user = Auth::user();
    $teacher = $user->teacher;
    return view('userzone.dashboard', compact('teacher', 'user'));
})->middleware(['auth', 'verified', 'ip.rate_limit'])->name('dashboard');

Route::middleware(['auth', 'is_teacher', 'ip.rate_limit'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('course', \App\Http\Controllers\CourseController::class);
    Route::get('/my_courses', [App\Http\Controllers\CourseController::class, 'user_courses'])->name('courses.my_courses');
    Route::get('/pick_courses', [App\Http\Controllers\CourseController::class, 'pick'])->name('course.pick');
    Route::post('/enroll_in/{course_id}', [App\Http\Controllers\CourseController::class, 'enroll_in'])->name('course.enroll_in');

    Route::get('/homework/search', [\App\Http\Controllers\HomeworkController::class, 'search'])->name('homework.search');
    Route::get('/homework/{courseId}', [\App\Http\Controllers\HomeworkController::class, 'index'])->name('homework.index');
    Route::get('/homework/show/{id}', [\App\Http\Controllers\HomeworkController::class, 'show'])->name('homework.show');
    Route::post('/homework/{id}', [\App\Http\Controllers\HomeworkController::class, 'store'])->name('homework.store');
    Route::post('/homework/evaluate/{id}', [\App\Http\Controllers\HomeworkController::class, 'evaluate'])->name('homework.evaluate');
    Route::post('/homework/reevaluate/{id}', [\App\Http\Controllers\HomeworkController::class, 'reEvaluate'])->name('homework.reEvaluate');

    Route::get('/enrolled_students/{courseId}', [\App\Http\Controllers\StudentController::class, 'index'])->name('student.enrolled');
    Route::get('/enrolled_student/{courseId}/{id}', [\App\Http\Controllers\StudentController::class, 'show'])->name('student.show');
});

require __DIR__ . '/auth.php';
