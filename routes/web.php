<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index']);

Route::get('/homework', [\App\Http\Controllers\HomeworkController::class, 'index']) -> name('homework.index');
Route::get('/homework/{id}', [\App\Http\Controllers\HomeworkController::class, 'show']) -> name('homework.show');

Route::resource('course', \App\Http\Controllers\CourseController::class);


Route::get('/dashboard', function () {
    $user = Auth::user();
    $teacher = $user->teacher;
    return view('userzone.dashboard', compact('teacher'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
