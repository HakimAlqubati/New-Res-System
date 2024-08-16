<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        // Assert the redirect
        $response->assertRedirect('/admin');

        // Follow the redirect and assert the final status is 200
        $response = $this->followingRedirects()->get('/admin');
        $response->assertStatus(200);
    }
}
