<?php

namespace App\Http\Controllers;

use App\Charts\EvaluatedHomeworksChart;
use App\Charts\TotalUsersChart;
use App\Models\Homework;
use Illuminate\Support\Facades\Cache;

class WelcomeController extends Controller
{
    function index(TotalUsersChart $chart, EvaluatedHomeworksChart $chart2)
    {
        $data = Cache::remember('welcome_page_data', 60 * 30, function () use ($chart, $chart2) {
            $total = Homework::count();
            $teacherHomeworks = Homework::whereHas('student.userData.teacher')->count();
            $evaluatedHomeworks = Homework::whereHas('evaluations')->count();

            return [
                'chart' => $chart->build(15, 10),
                'chart2' => $chart2->build($teacherHomeworks, $evaluatedHomeworks, $total)
            ];
        });

        return view('welcome', $data);
    }
}