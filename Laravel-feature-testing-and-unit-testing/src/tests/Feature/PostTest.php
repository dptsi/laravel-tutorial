<?php

namespace Tests\Feature;

use App\Modules\Post\Core\Domain\Model\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_welcome_status()
    {
        $response = $this->get('/post');

        $response->assertStatus(200);
    }

    public function test_welcome_view()
    {
        $post = Post::factory()->create();
        $view = $this->view('Post::welcome', ['posts' => [$post]]);

        $view->assertSee($post->title);
    }

    public function test_create_status()
    {
        $response = $this->get('/post/create');

        $response->assertStatus(200);
    }
}
