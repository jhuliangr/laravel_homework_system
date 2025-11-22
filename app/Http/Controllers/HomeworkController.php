<?php

namespace App\Http\Controllers;

use App\Models\CourseStudent;
use App\Models\Evaluation;
use App\Models\Homework;
use Illuminate\Http\Request;

class HomeWorkController extends Controller
{
    public function index(Request $request, string $courseId)
    {
        $user = auth()->user();
        $courseStudent = CourseStudent::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();

        if (!$courseStudent) {
            $homeworks = Homework::whereIn('course_student_id', function ($query) use ($courseId) {
                $query->select('id')
                    ->from('course_students')
                    ->where('course_id', $courseId);
            })->with('student', 'evaluations')->paginate(5);
            $withStudentName = true;
            return view('homework.index', compact('homeworks', 'courseId', 'withStudentName'));
        }
        $homeworks = Homework::where('course_student_id', $courseStudent->id)->with('student', 'evaluations')->paginate(5);

        return view('homework.index', compact('homeworks', 'courseId'));
    }

    public function show(Request $request, string $id)
    {
        $highestScore = "";
        $homework = Homework::find($id);
        if (!$homework->evaluations->isEmpty()) {
            $highestScore = $homework->evaluations->max('value');
        }
        $isTeacher = $request->is_teacher;
        return view('homework.show', compact('homework', 'highestScore', 'isTeacher'));
    }

    public function store(Request $request, string $id)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'body' => ['required', 'string', 'min:5', 'max:3000'],
        ]);

        $courseStudentId = $user->courseStudent()->where('course_id', $id)->first()->id;

        Homework::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'course_student_id' => $courseStudentId
        ]);

        return redirect(route('homework.index', $id));
    }
    public function evaluate(Request $request, string $id)
    {
        Evaluation::create([
            'homework_id' => $id,
            'value' => $request->evaluation,
            'teacher_id' => auth()->user()->teacher->id
        ]);
        return redirect(route('homework.show', $id));
    }
    public function reEvaluate(Request $request, string $id)
    {
        $evaluation = Evaluation::where('homework_id', $id);
        $evaluation->update([
            'value' => $request->evaluation,
            'teacher_id' => auth()->user()->teacher->id
        ]);

        return redirect(route('homework.show', $id));
    }
}
