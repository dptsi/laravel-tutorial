<?php

namespace Tests\Feature;

use App\Modules\Post\Core\Domain\Model\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
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

    public function test_post_with_headers()
    {
        // $response = $this->withHeaders([
        //     'X-Header' => 'Value',
        // ])->post('/post', ['name' => 'Sally']);

        // $response->assertStatus(201);
    }

    public function test_post_with_cookies()
    {
        // $response = $this->withCookie('color', 'blue')->get('/post');

        // $response = $this->withCookies([
        //     'color' => 'blue',
        //     'name' => 'Taylor',
        // ])->get('/post');
    }

    public function test_post_with_the_session()
    {
        $response = $this->withSession(['banned' => false])->get('/post');
    }

    public function test_post_requires_authentication()
    {
        // $user = User::factory()->create();

        // $response = $this->actingAs($user)
        //     ->withSession(['banned' => false])
        //     ->get('/post');
    }

    public function test_basic_test()
    {
        $response = $this->get('/post');

        $response->dumpHeaders();

        $response->dumpSession();

        $response->dump();
    }

    public function test_post_json_request()
    {
        $response = $this->postJson('/post', ['title' => 'Test Post', 'description' => 'Test Description']);

        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);

        // $response
        //     ->assertStatus(201)
        //     ->assertExactJson([
        //         'created' => true,
        //     ]);

        // $response
        // ->assertStatus(201)
        // ->assertJsonPath('team.owner.name', 'Darian');

        // $response
        //     ->assertJson(
        //         fn (AssertableJson $json) =>
        //         $json->where('id', 1)
        //             ->where('name', 'Victoria Faith')
        //             ->missing('password')
        //             ->etc()
        //     );
    }

    public function test_get_all_json()
    {
        $post = Post::factory()->create();
        $response = $this->get('/post');

        // $response
        //     ->assertJson(
        //         fn (AssertableJson $json) =>
        //         $json->has(3)
        //             ->first(
        //                 fn ($json) =>
        //                 $json->where('id', 1)
        //                     ->where('name', 'Victoria Faith')
        //                     ->missing('password')
        //                     ->etc()
        //             )
        //     );
    }
}
