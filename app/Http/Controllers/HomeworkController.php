<?php

namespace App\Http\Controllers;

use App\Models\CourseStudent;
use App\Models\Evaluation;
use App\Models\Homework;
use App\Notifications\HomeworkQualifiedNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HomeworkController extends Controller
{
    public function index(Request $request, string $courseId)
    {
        $user = auth()->user();

        // Get student data for authenticated user from the many to many relation with course
        $courseStudent = CourseStudent::select('id')->where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();

        // Checks $courseStudent and, if it's the authenticated user is not a course student it looks for the homeworks attached 
        // to a course and makes the view to show the student name
        if (!$courseStudent) {
            $homeworks = Homework::whereIn('course_student_id', function ($query) use ($courseId) {
                $query->select('id')
                    ->from('course_students')
                    ->where('course_id', $courseId);
            })->with('student', 'evaluations')->paginate(5);
            $withStudentName = true;

            return view('homework.index', compact('homeworks', 'courseId', 'withStudentName'));
        }
        $homeworks = Homework::select('id', 'title')->where('course_student_id', $courseStudent->id)->with('student:userData:name', 'evaluations:value')->paginate(5);

        return view('homework.index', compact('homeworks', 'courseId'));
    }

    public function show(Request $request, string $id)
    {
        $highestScore = "";
        $homework = Homework::select('id', 'title', 'body', 'course_student_id')->find($id);
        if (!$homework->evaluations->isEmpty()) {
            $highestScore = $homework->evaluations->max('value');
        }
        $isTeacher = $request->is_teacher;
        // If the student is a teacher and is the one who uploaded the actual homework, will not have evaluation permission 
        $studentIsTeacher = $homework->student->userData->teacher;
        if ($isTeacher && $studentIsTeacher) {
            $teacherIdFromAuth = auth()->user()->teacher->id;
            $isTeacher = $studentIsTeacher->id != $teacherIdFromAuth;
        }

        return view('homework.show', compact('homework', 'highestScore', 'isTeacher'));
    }

    public function store(Request $request, string $id)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'body' => ['required', 'string', 'min:5', 'max:3000'],
        ]);
        // Get the id of the course the task is uploaded for, if it doesn't exists throw 404 error
        $courseStudentId = $user->courseStudent()->where('course_id', $id)->first()->id;
        if (!$courseStudentId) {
            abort(404);
        }

        Homework::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'course_student_id' => $courseStudentId
        ]);
        $this->clearHomeworkSearchCache();

        return redirect(route('homework.index', $id));
    }
    public function evaluate(Request $request, string $id)
    {
        if (!$request->is_teacher) {
            return abort(403);
        }
        $teacher = auth()->user()->teacher;
        $homework = Homework::select('id', 'course_student_id')->find($id);
        $userData = $homework->student->userData;

        // if the teacher is also the student he can't evaluate himself
        if ($teacher->data->id == $userData->id) {
            return abort(403);
        }

        if (!$teacher || !$homework) {
            abort(404);
        }

        $validated = $request->validate([
            'evaluation' => [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4, 5])
            ],
        ]);

        Evaluation::create([
            'homework_id' => $id,
            'value' => $validated['evaluation'],
            'teacher_id' => auth()->user()->teacher->id
        ]);

        // Send an email to the student who uploaded the homework
        $userData->notify(new HomeworkQualifiedNotification($homework));

        return redirect(route('homework.show', $id));
    }
    public function reEvaluate(Request $request, string $id)
    {
        if (!$request->is_teacher) {
            return abort(403);
        }

        $teacher = auth()->user()->teacher;

        $evaluation = Evaluation::where('homework_id', $id)->first();
        if (!$evaluation) {
            abort(404);
        }

        $homework = Homework::select('id', 'course_student_id')->find($id);
        $userData = $homework->student->userData;

        // if the teacher is also the student he can't evaluate himself
        if ($teacher->data->id == $userData->id) {
            return abort(403);
        }

        $validated = $request->validate([
            'evaluation' => [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4, 5])
            ],
        ]);
        $evaluation->update([
            'value' => $validated['evaluation'],
            'teacher_id' => auth()->user()->teacher->id
        ]);

        $homework = $evaluation->homework;
        // Send an email to the student who uploaded the homework
        $userData->notify(new HomeworkQualifiedNotification($homework));

        return redirect(route('homework.show', $id));
    }


    public function search(Request $request)
    {
        if (!$request->is_teacher) {
            return abort(403);
        }

        $search = $request->input('search');

        // Create unique key for the page and search input value
        $page = $request->get('page', 1);
        $cacheKey = 'homework_search_' . md5($search . '_' . $page);

        // Save the unique key under the array homework_search_keys for being able to delete them when a new homework is uploaded
        $keys = cache()->get('homework_search_keys', []);
        $keys[] = $cacheKey;
        cache()->put('homework_search_keys', $keys);

        // Get the homeworks from cache or make the query for retrieving the homeworks from database
        $homeworks = cache()->remember($cacheKey, now()->addHour(), function () use ($search) {
            return Homework::with('student.userData', 'course')
                ->when($search, function ($query, $search) {
                    return $query->where(function ($q) use ($search) {
                        // Search by homework title
                        $q->where('title', 'LIKE', "%{$search}%")
                            // Search by user name
                            ->orWhereHas('student', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'LIKE', "%{$search}%");
                        })
                            // Search by user email
                            ->orWhereHas('student', function ($userQuery) use ($search) {
                            $userQuery->where('email', 'LIKE', "%{$search}%");
                        })
                            // Search by course name
                            ->orWhereHas('course', function ($courseQuery) use ($search) {
                            $courseQuery->where('module_name', 'LIKE', "%{$search}%");
                        });
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(5);
        });

        return view('homework.search', compact('homeworks', 'search'));
    }

    private function clearHomeworkSearchCache()
    {
        $CACHEKEY = 'homework_search_keys';
        $keys = cache()->get($CACHEKEY, []);

        foreach ($keys as $key) {
            cache()->forget($key);
        }

        cache()->forget($CACHEKEY);
    }
}
