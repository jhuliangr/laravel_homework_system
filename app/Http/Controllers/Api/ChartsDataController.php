<?php

namespace App\Http\Controllers;

use App\Charts\EvaluatedHomeworksChart;
use App\Charts\TotalUsersChart;
use App\Models\Homework;
use Illuminate\Support\Facades\Cache;

class ChartsDataController extends Controller
{
    public function index(TotalUsersChart $chart, EvaluatedHomeworksChart $chart2)
    {
        $data = Cache::remember('charts_data', 60 * 30, function () use ($chart, $chart2) {
            $total = Homework::count();
            $teacherHomeworks = Homework::whereHas('student.userData.teacher')->count();
            $evaluatedHomeworks = Homework::whereHas('evaluations')->count();

            return [
                'total' => $total,
                'teacherHomeworks' => $teacherHomeworks,
                'evaluatedHomeworks' => $evaluatedHomeworks,
            ];
        });

        return view('welcome', $data);
    }
}