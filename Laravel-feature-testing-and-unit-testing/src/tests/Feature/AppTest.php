<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_homepage_status()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_get_homepage_view()
    {
        $view = $this->view('welcome');

        $view->assertSee('Do you want to become a developer?');
    }
}
