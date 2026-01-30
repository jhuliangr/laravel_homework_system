<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can logout', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->post('/logout');
    $response->assertRedirect('/');
    $this->assertGuest();
});
