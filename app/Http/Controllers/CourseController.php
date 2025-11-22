<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseStudent;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $courses = $request->is_teacher ? $request->user()->teacher->courses() : Course::all();
        return view('course.index', compact('courses', 'user'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'module_name' => ['required', 'string', 'min:5', 'max:255'],
            'start_date' => ['required', 'date'],
        ]);

        $course = Course::create([
            'module_name' => $validated['module_name'],
            'start_date' => $validated['start_date'],
            'teacher_id' => $user->teacher->id,
        ]);

        return redirect('/course');
    }

    public function show(string $id)
    {
        $course = Course::find($id);
        $user = auth()->user();
        $edit = $user->teacher && $course->teacher_id === $user->teacher->id;

        return view('course.show', compact('course', 'user', 'edit'));
    }

    public function edit(string $id)
    {
        $course = Course::find($id);

        return view('course.show', compact('course'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'module_name' => ['required', 'string', 'min:5', 'max:255'],
            'start_date' => ['required', 'date'],
        ]);

        $course = Course::find($id);
        $course->update($validated);

        return view('course.show', compact('course'));
    }

    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('course.index');
    }

    // Extra functions
    public function user_courses()
    {
        $user = auth()->user();
        $courses = $user->courses()->paginate(5);
        $show = false;

        return view('course.index', compact('courses', 'user'));
    }

    public function pick()
    {
        $user = auth()->user();

        $excluded_course_ids = $user->courses->pluck('id');

        if ($user->teacher) {
            $excluded_course_ids = $excluded_course_ids->merge($user->teacher->courses->pluck('id'))->unique();
        }

        $courses = Course::whereNotIn('id', $excluded_course_ids)->paginate(5);

        return view('course.pick', compact('courses'));
    }

    public function enroll_in($course_id)
    {
        $user = auth()->user();
        CourseStudent::create([
            'user_id' => $user->id,
            'course_id' => $course_id,
        ]);

        return redirect()->route('course.pick');
    }
}
