<?php

use App\Http\Controllers\Api\HomeworkPublicApiController;
use App\Http\Controllers\ChartsDataController;
use Illuminate\Support\Facades\Route;

Route::post('mollie/webhook', \App\Http\Controllers\Api\MollieWebhookController::class)->name('api.mollie.webhook');

Route::middleware('auth:sanctum')->group(function () {
  Route::get('charts-info', [ChartsDataController::class, 'index']);
  Route::get('/courses/{courseId}/homeworks', [HomeworkPublicApiController::class, 'index']);
  Route::post('/courses/{courseId}/homeworks', [HomeworkPublicApiController::class, 'store']);

  Route::get('/homeworks/{id}', [HomeworkPublicApiController::class, 'show']);
  Route::get('/homeworks/search', [HomeworkPublicApiController::class, 'search']);

  Route::post('/homeworks/{id}/evaluate', [HomeworkPublicApiController::class, 'evaluate']);
  Route::put('/homeworks/{id}/evaluate', [HomeworkPublicApiController::class, 'reEvaluate']);
});