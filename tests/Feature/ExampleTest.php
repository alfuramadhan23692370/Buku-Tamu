<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
{
    $response = $this->get('/');

    // Mengubah ekspektasi dari 200 menjadi redirect 302
    $response->assertRedirect('/login'); 
}
}
