<?php

namespace App\Http\Controllers\Api;

use App\Charts\EvaluatedHomeworksChart;
use App\Charts\TotalUsersChart;
use App\Http\Controllers\Controller;
use App\Models\Homework;
use Illuminate\Support\Facades\Cache;

class ChartsDataController extends Controller
{
    public function index()
    {
        $total = Homework::count();
        $teacherHomeworks = Homework::whereHas('student.userData.teacher')->count();
        $evaluatedHomeworks = Homework::whereHas('evaluations')->count();

        return response()->json([
            'total' => $total,
            'teacherHomeworks' => $teacherHomeworks,
            'evaluatedHomeworks' => $evaluatedHomeworks,
        ]);
    }
}