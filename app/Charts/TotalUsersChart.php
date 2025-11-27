<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class TotalUsersChart extends LarapexChart
{
  protected $chart;

  public function __construct(LarapexChart $chart)
  {
    $this->chart = $chart;
  }

  public function build(int $users, int $teachers)
  {
    return $this->chart->pieChart()
      ->addData(
        [$users - $teachers, $teachers]
      )
      ->setTitle('Total users')
      ->setLabels(['Students', 'Teachers']);
  }
}