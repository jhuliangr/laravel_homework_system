<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class EvaluatedHomeworksChart extends LarapexChart
{
  protected $chart;

  public function __construct(LarapexChart $chart)
  {
    $this->chart = $chart;
  }

  public function build(
    int $homeworksUploadedByTeachers,
    int $evaluatedHomeworks,
    int $total
  ) {

    $homeworkPercentByStudents = $total != 0 ? (int) ((($total - $homeworksUploadedByTeachers) * 100) / $total) : 0;
    $homeworkPercentByTeachers = $total != 0 ? (int) (($homeworksUploadedByTeachers * 100) / $total) : 0;
    $homeworksEvaluatedPecent = $total != 0 ? (int) (($evaluatedHomeworks * 100) / $total) : 0;


    return $this->chart->radialChart()
      ->addData([$homeworkPercentByStudents, $homeworkPercentByTeachers, $homeworksEvaluatedPecent])
      ->setTitle('Homeworks numbers.')
      ->setLabels(['Homeworks by students', 'Homeworks by Teachers', "Evaluated Homeworks"])
      ->setColors(['#10FFCB', '#A09BE7', '#5F00BA']);
  }
}