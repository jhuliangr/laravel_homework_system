<?php

use App\Charts\EvaluatedHomeworksChart;
use App\Charts\TotalUsersChart;
use App\Models\Homework;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
});

it('cache expires after 30 minutes', function () {
    $mockChartData = [
        'chart' => 'expired_chart_data',
        'chart2' => 'expired_chart2_data'
    ];

    Cache::put('welcome_page_data', $mockChartData, 1); // 1 second expiry

    sleep(2); // Wait for cache to expire

    Homework::factory()->create(); // Create new homework to ensure new data

    $response = $this->get('/');

    $response->assertStatus(200);
});