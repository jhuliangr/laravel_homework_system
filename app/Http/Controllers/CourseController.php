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
        // if the middleware says it's a teacher bring the courses this teacher imparts, otherwise bring all the courses
        $courses = $request->is_teacher ?
            $request->user()->teacher->courses()->select('id', 'start_date', 'module_name')->paginate(5) :
            Course::select('id', 'start_date', 'module_name')->paginate(5);

        return view('course.index', compact('courses', 'user'));
    }

    // This function is not being used because i've created a modal for creating courses
    public function create()
    {
    }

    public function store(Request $request)
    {
        // If the authenticated user is not a teacher, will receive 403 error
        if (!$request->is_teacher) {
            return abort(403);
        }
        $user = auth()->user();

        $validated = $request->validate([
            'module_name' => ['required', 'string', 'min:5', 'max:255'],
            'start_date' => ['required', 'date'],
        ]);

        Course::create([
            'module_name' => $validated['module_name'],
            'start_date' => $validated['start_date'],
            'teacher_id' => $user->teacher->id,
        ]);

        return redirect(route('course.index'));
    }

    public function show(Request $request, string $id)
    {
        $course = Course::select('id', 'module_name', 'start_date', 'teacher_id')->find($id);
        $user = auth()->user();
        // if the authenticated user is the owner of the course, it's allowed to edit it, otherwise no 
        $edit = $request->is_teacher && $course->teacher_id === $user->teacher->id;

        return view('course.show', compact('course', 'user', 'edit'));
    }

    // This function is not being used because i've created a modal for updating courses
    public function edit(string $id)
    {
    }

    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $course = Course::select('id', 'module_name', 'start_date', 'teacher_id')->find($id);

        // If the authenticated user is not a teacher and if it is, is not the owner of the course, will receive 403 error
        if (!($request->is_teacher && $course->teacher_id === $user->teacher->id)) {
            return abort(403);
        }

        $validated = $request->validate([
            'module_name' => ['required', 'string', 'min:5', 'max:255'],
            'start_date' => ['required', 'date'],
        ]);

        $course->update($validated);

        // if reached this point, the teacher is indeed the owner of the course, so he has edit setted on true
        $edit = true;

        return view('course.show', compact('course', 'user', 'edit'));
    }

    public function destroy(Request $request, string $id)
    {
        $course = Course::findOrFail($id)->select('teacher_id');
        $user = auth()->user();

        // If the authenticated user is not a teacher and if it is, is not the owner of the course, will receive 403 error
        if (!($request->is_teacher && $course->teacher_id === $user->teacher->id)) {
            return abort(403);
        }
        $course->delete();

        return redirect()->route('course.index');
    }
    //............................................................................ 
    // Extra functions
    //............................................................................

    // Function for retrieving the user's enrolled courses from database
    public function user_courses()
    {

        $user = auth()->user();
        $courses = $user->courses()->select('user:id', 'start_date', 'module_name')->paginate(5);

        return view('course.index', compact('courses', 'user'));
    }

    // Function for choosing which courses to enroll in
    public function pick(Request $request)
    {
        $user = auth()->user();

        // Get the authenticated user enrolled courses ids for excluding them from the courses the user can enroll in
        $excluded_course_ids = $user->courses->select('id')->pluck('id');

        // If the middleware says the authenticated user is a teacher then mix the ids of the actually enrolled courses with the courses this teacher imparts
        // because a teacher can't teach to himself. At least is not the most common case.
        if ($request->is_teacher) {
            $excluded_course_ids = $excluded_course_ids->merge($user->teacher->courses->pluck('id'))->unique();
        }

        // retrieve from database the courses excluding the unnecesary ones
        $courses = Course::whereNotIn('id', $excluded_course_ids)->select('id', 'module_name', 'start_date', 'teacher_id')->paginate(5);

        return view('course.pick', compact('courses'));
    }

    // function to enroll in a course
    public function enroll_in($course_id)
    {
        $user = auth()->user();

        // Find course by it's id and if dones't find it, to throw a 404 error
        Course::findOrFail($course_id)->select('id');

        CourseStudent::create([
            'user_id' => $user->id,
            'course_id' => $course_id,
        ]);

        return redirect()->route('course.pick');
    }
}
