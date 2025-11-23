<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        // get authenticated user
        $courses = $request->is_teacher ? $request->user()->teacher->courses()->paginate(5) : Course::paginate(5);
        // if the middleware says it's a teacher bring the courses this teacher imparts, otherwise shows all the courses

        return view('course.index', compact('courses', 'user'));
    }

    public function create()
    {
        // This function is not being used because i've created a modal for creating courses
    }

    public function store(Request $request)
    {
        if (!$request->is_teacher) {
            // If the authenticated user is not a teacher, will receive 403 error
            return abort(403);
        }
        $user = auth()->user();
        // get authenticated user

        $validated = $request->validate([
            'module_name' => ['required', 'string', 'min:5', 'max:255'],
            'start_date' => ['required', 'date'],
        ]);
        // Validate data incomming for creating the new course

        Course::create([
            'module_name' => $validated['module_name'],
            'start_date' => $validated['start_date'],
            'teacher_id' => $user->teacher->id,
        ]);
        // create the course

        return redirect('/course');
    }

    public function show(Request $request, string $id)
    {
        $course = Course::find($id);
        // retrieve from database the course data by it's id
        $user = auth()->user();
        // get the authenticated user's data
        $edit = $request->is_teacher && $course->teacher_id === $user->teacher->id;
        // if the authenticated user is the owner of the course, it's allowed to edit it, otherwise no 

        return view('course.show', compact('course', 'user', 'edit'));
    }

    public function edit(string $id)
    {
        // This function is not being used because i've created a modal for updating courses
    }

    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        // get the authenticated user's data
        $course = Course::find($id);
        // retrieve from database the course data by it's id
        if (!($request->is_teacher && $course->teacher_id === $user->teacher->id)) {
            // If the authenticated user is not a teacher and if it is, is not the owner of the course, will receive 403 error
            return abort(403);
        }

        $validated = $request->validate([
            'module_name' => ['required', 'string', 'min:5', 'max:255'],
            'start_date' => ['required', 'date'],
        ]);
        // Validate data incomming for updating the course

        $course->update($validated);
        // update course with the incomming data

        $edit = true;
        // if reached this point, the teacher is indeed the owner of the course, so he has edit setted on true

        return view('course.show', compact('course', 'user', 'edit'));
    }

    public function destroy(Request $request, string $id)
    {
        $course = Course::findOrFail($id);
        // Find course by it's id and if dones't find it, to throw a 404 error
        $user = auth()->user();
        // Get the authenticated user's data

        if (!($request->is_teacher && $course->teacher_id === $user->teacher->id)) {
            // If the authenticated user is not a teacher and if it is, is not the owner of the course, will receive 403 error
            return abort(403);
        }

        $course->delete();
        // Delete the course

        return redirect()->route('course.index');
    }

    // Extra functions
    public function user_courses()
    // Function for retrieving the user's enrolled courses from database
    {
        $user = auth()->user();
        // Get the authenticated user's data

        $courses = $user->courses()->paginate(5);
        // Get the authenticated user's courses

        return view('course.index', compact('courses', 'user'));
    }

    public function pick(Request $request)
    // Function for choosing which courses to enroll in
    {
        $user = auth()->user();
        // Get the authenticated user's data

        $excluded_course_ids = $user->courses->pluck('id');
        // Get the authenticated user enrolled courses ids for excluding them from the courses the user can enroll in

        if ($request->is_teacher) {
            $excluded_course_ids = $excluded_course_ids->merge($user->teacher->courses->pluck('id'))->unique();
            // If the middleware says the authenticated user is a teacher then mix the ids of the actually enrolled courses with the courses this teacher imparts
            // because a teacher can't teach to himself. At least is not the most common case.
        }

        $courses = Course::whereNotIn('id', $excluded_course_ids)->paginate(5);
        // retrieve from database the courses excluding the unnecesary ones

        return view('course.pick', compact('courses'));
    }

    public function enroll_in($course_id)
    // function to enroll in a course
    {
        $user = auth()->user();
        // Get the authenticated user's data

        User::findOrFail($user->id);
        // Find user by it's id and if dones't find it, to throw a 404 error

        Course::findOrFail($course_id);
        // Find course by it's id and if dones't find it, to throw a 404 error

        CourseStudent::create([
            'user_id' => $user->id,
            'course_id' => $course_id,
        ]);
        // create the relation between the user and the course

        return redirect()->route('course.pick');
    }
}
