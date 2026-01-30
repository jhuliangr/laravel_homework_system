<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourseStudent;
use App\Models\Evaluation;
use App\Models\Homework;
use App\Notifications\HomeworkQualifiedNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class HomeworkPublicApiController extends Controller
{
    /**
     * GET /api/courses/{courseId}/homeworks
     */
    public function index(Request $request, string $courseId): JsonResponse
    {
        $user = auth()->user();

        $courseStudent = CourseStudent::select('id')
            ->where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();

        if (!$courseStudent) {
            $homeworks = Homework::whereIn('course_student_id', function ($query) use ($courseId) {
                $query->select('id')
                    ->from('course_students')
                    ->where('course_id', $courseId);
            })
                ->with(['student.userData', 'evaluations'])
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $homeworks,
                'meta' => [
                    'with_student_name' => true,
                    'course_id' => $courseId
                ]
            ]);
        }

        $homeworks = Homework::select('id', 'title', 'created_at')
            ->where('course_student_id', $courseStudent->id)
            ->with(['student.userData:name', 'evaluations:value,homework_id'])
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $homeworks,
            'meta' => [
                'course_id' => $courseId,
                'student_id' => $courseStudent->id
            ]
        ]);
    }

    /**
     * GET /api/homeworks/{id}
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $homework = Homework::select('id', 'title', 'body', 'course_student_id', 'created_at')
            ->with(['student.userData', 'evaluations.teacher'])
            ->find($id);

        if (!$homework) {
            return response()->json([
                'success' => false,
                'message' => 'Tarea no encontrada'
            ], 404);
        }

        $highestScore = $homework->evaluations->isNotEmpty()
            ? $homework->evaluations->max('value')
            : null;

        $isTeacher = $request->has('is_teacher') ? $request->is_teacher : false;

        $studentIsTeacher = $homework->student->userData->teacher ?? null;

        if ($isTeacher && $studentIsTeacher) {
            $teacherIdFromAuth = auth()->user()->teacher->id;
            $isTeacher = $studentIsTeacher->id != $teacherIdFromAuth;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'homework' => $homework,
                'highest_score' => $highestScore,
                'evaluation_permission' => $isTeacher,
                'total_evaluations' => $homework->evaluations->count()
            ]
        ]);
    }

    /**
     * POST /api/courses/{courseId}/homeworks
     */
    public function store(Request $request, string $courseId): JsonResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'body' => ['required', 'string', 'min:5', 'max:3000'],
        ]);

        $courseStudent = $user->courseStudent()->where('course_id', $courseId)->first();

        if (!$courseStudent) {
            return response()->json([
                'success' => false,
                'message' => 'You are not a student of this course'
            ], 404);
        }

        $homework = Homework::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'course_student_id' => $courseStudent->id
        ]);

        $this->clearHomeworkSearchCache();

        return response()->json([
            'success' => true,
            'message' => 'Homework created successfully',
            'data' => $homework
        ], 201);
    }

    /**
     * POST /api/homeworks/{id}/evaluate
     */
    public function evaluate(Request $request, string $id): JsonResponse
    {
        $user = auth()->user();
        if (!$user->teacher) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to evaluate homeworks'
            ], 403);
        }

        $teacher = $user->teacher;
        $homework = Homework::select('id', 'course_student_id')
            ->with('student.userData')
            ->find($id);

        if (!$homework) {
            return response()->json([
                'success' => false,
                'message' => 'Homework not found'
            ], 404);
        }

        $userData = $homework->student->userData;

        if ($teacher->data->id == $userData->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot evaluate yourself'
            ], 403);
        }

        $validated = $request->validate([
            'value' => [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4, 5])
            ],
        ]);

        $existingEvaluation = Evaluation::where('homework_id', $id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if ($existingEvaluation) {
            return response()->json([
                'success' => false,
                'message' => 'You have already evaluated this homework, update your evaluation instead'
            ], 409);
        }

        $evaluation = Evaluation::create([
            'homework_id' => $id,
            'value' => $validated['value'],
            'teacher_id' => $teacher->id
        ]);

        $userData->notify(new HomeworkQualifiedNotification($homework));

        return response()->json([
            'success' => true,
            'message' => 'Homework evaluated successfully',
            'data' => $evaluation
        ], 201);
    }

    /**
     * PUT /api/homeworks/{id}/evaluate
     */
    public function reEvaluate(Request $request, string $id): JsonResponse
    {
        $user = auth()->user();
        if (!$user->teacher) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to re-evaluate homeworks'
            ], 403);
        }

        $teacher = $user->teacher;

        $evaluation = Evaluation::where('homework_id', $id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => 'There is no evaluation for this homework'
            ], 404);
        }

        $homework = Homework::select('id', 'course_student_id')
            ->with('student.userData')
            ->find($id);

        if (!$homework) {
            return response()->json([
                'success' => false,
                'message' => 'Homework not found'
            ], 404);
        }

        $userData = $homework->student->userData;

        if ($teacher->data->id == $userData->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot re-evaluate yourself'
            ], 403);
        }

        $validated = $request->validate([
            'value' => [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4, 5])
            ],
        ]);

        $evaluation->update([
            'value' => $validated['value'],
            'teacher_id' => $teacher->id
        ]);

        $userData->notify(new HomeworkQualifiedNotification($homework));

        return response()->json([
            'success' => true,
            'message' => 'Homework re-evaluated successfully',
            'data' => $evaluation
        ]);
    }

    /**
     * GET /api/homeworks/search
     */
    public function search(Request $request): JsonResponse
    {
        $user = auth()->user();
        if (!$user->teacher) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to search for homeworks'
            ], 403);
        }

        $search = $request->input('search', '');

        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 15);
        $cacheKey = 'homework_search_' . md5($search . '_' . $page . '_' . $perPage);

        $keys = cache()->get('homework_search_keys', []);
        $keys[] = $cacheKey;
        cache()->put('homework_search_keys', array_unique($keys));

        $homeworks = cache()->remember($cacheKey, now()->addHour(), function () use ($search, $perPage) {
            return Homework::with(['student.userData', 'course', 'evaluations'])
                ->when($search, function ($query, $search) {
                    return $query->where(function ($q) use ($search) {
                        $q->where('title', 'LIKE', "%{$search}%")
                            ->orWhereHas('student.userData', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'LIKE', "%{$search}%");
                        })
                            ->orWhereHas('student', function ($userQuery) use ($search) {
                            $userQuery->where('email', 'LIKE', "%{$search}%");
                        })
                            ->orWhereHas('course', function ($courseQuery) use ($search) {
                            $courseQuery->where('module_name', 'LIKE', "%{$search}%");
                        });
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
        });

        return response()->json([
            'success' => true,
            'data' => $homeworks,
            'meta' => [
                'search_query' => $search,
                'cached' => cache()->has($cacheKey)
            ]
        ]);
    }

    private function clearHomeworkSearchCache(): void
    {
        $CACHEKEY = 'homework_search_keys';
        $keys = cache()->get($CACHEKEY, []);

        foreach ($keys as $key) {
            cache()->forget($key);
        }

        cache()->forget($CACHEKEY);
    }
}