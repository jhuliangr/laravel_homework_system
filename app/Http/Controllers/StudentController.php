<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Homework;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $courseId)
    {
        if (!$request->is_teacher) {
            abort(404);
        }
        $courseStudents = Course::where("id", $courseId)->first()->students()->select('user:id', 'name', 'email')->paginate(5);
        return view("student.index", compact("courseStudents", "courseId"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $courseId, string $id)
    {
        if (!$request->is_teacher) {
            abort(404);
        }
        $student = User::select('name', 'email')->find($id);

        $courseStudentId = CourseStudent::where("course_id", $courseId)->where("user_id", $id)->first()->id;
        if (!$courseStudentId) {
            abort(404);
        }
        $homeworksUploadedInCourse = Homework::where("course_student_id", $courseStudentId)->with('evaluations:value', 'student:userData:name')->select('id', 'title')
            ->paginate(5);

        return view("student.show", compact("student", "homeworksUploadedInCourse", "courseId"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
