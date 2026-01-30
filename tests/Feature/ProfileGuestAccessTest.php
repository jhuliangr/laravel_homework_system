<?php

test('guest is redirected to login when accessing profile', function () {
    $response = $this->get('/profile');
    $response->assertRedirect('/login');
});
