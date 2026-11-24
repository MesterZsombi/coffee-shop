<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the coffee shop homepage loads successfully', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Our Menu');
});
