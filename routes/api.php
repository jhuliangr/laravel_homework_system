<?php

use Illuminate\Support\Facades\Route;

Route::post('mollie/webhook', \App\Http\Controllers\Api\MollieWebhookController::class)->name('api.mollie.webhook');
Route::get('azucar', function () {
  return response()->json(['message' => 'Hola, azucar!']);
})->name('api.azucar');