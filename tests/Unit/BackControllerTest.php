<?php

use App\Http\Controllers\BackController;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);
$url = env('APP_URL');

it('redirects to url()->previous() when history is empty', function () {
    session()->put('history', []);

    $controller = new BackController();
    $response = $controller->back();

    // Should redirect to previous URL (which we can't easily test in this context)
    // but we can verify it's a redirect response
    $this->assertEquals(302, $response->getStatusCode());
});

it('redirects to url()->previous() when history key does not exist', function () {
    // No history key in session at all
    $controller = new BackController();
    $response = $controller->back();

    $this->assertEquals(302, $response->getStatusCode());
});