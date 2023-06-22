<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_redirect_to_login_page()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
        $response->assertSee('login');
    }
}
