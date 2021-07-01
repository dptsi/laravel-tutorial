<?php

namespace Tests\Unit\Post;

use App\Modules\Post\Core\Domain\Model\Post;
use App\Modules\Post\Core\Domain\Repository\PostRepository;
use App\Modules\Post\Infrastructure\Repository\MySQLPostRepository;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Mockery;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testStoreDataSuccessfullyPost()
    {
        $repo = Mockery::mock(MySQLPostRepository::class);

        $repo->shouldReceive('store')->once();
        app()->instance(PostRepository::class, $repo);
        $response = $this->post('/post', [
            '_token' => csrf_token(),
            'title' => 'test',
            'description' => 'description'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/post');
    }

    public function testStoreDataFailedPost()
    {
        $repo = Mockery::mock(MySQLPostRepository::class);

        $repo->shouldReceive('store');
        app()->instance(PostRepository::class, $repo);
        $response = $this->post('/post', [
            'title' => ''
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    public function testRenderAllPostsPage()
    {
        $repo = Mockery::mock(MySQLPostRepository::class);
        $posts = [];
        for ($i = 0; $i < 4; $i++) {
            $post = new Post();
            $post->title = 'dasar' . $i;
            $post->description = 'description' . $i;
            $post->slug = 'dasard' . $i;
            array_push($posts, $post);
        }
        $repo->shouldReceive('getAll')->andReturn($posts);
        app()->instance(PostRepository::class, $repo);
        $response = $this->get('/post');
        $response->assertStatus(200);
        $response->assertSeeText($posts[0]->title);
    }

    public function testRenderShowOnePostPage()
    {
        $repo = Mockery::mock(MySQLPostRepository::class);
        $post = new Post();
        $post->title = 'dasar';
        $post->description = 'description';
        $post->slug = 'dasar';
        $post->createdAt = now();
        $post->updatedAt = now();
        $repo->shouldReceive('findBySlug')->andReturn($post);
        app()->instance(PostRepository::class, $repo);
        $response = $this->get('/post/' . $post->slug);
        $response->assertSeeText($post->title);
    }

    public function testRenderShowEditPostPage()
    {
        $repo = Mockery::mock(MySQLPostRepository::class);
        $post = new Post();
        $post->title = 'dasar';
        $post->description = 'description';
        $post->slug = 'dasar';
        $post->createdAt = now();
        $post->updatedAt = now();
        $repo->shouldReceive('findBySlug')->andReturn($post);
        app()->instance(PostRepository::class, $repo);
        $response = $this->get('/post/' . $post->slug . '/edit');
        $response->assertStatus(200);
        $response->assertSeeText('Edit Post');
    }

    public function testEditDataSuccessfullyPost()
    {
        $repo = Mockery::mock(MySQLPostRepository::class);

        $repo->shouldReceive('update')->once();
        app()->instance(PostRepository::class, $repo);
        $response = $this->put('/post/dasar', [
            '_token' => csrf_token(),
            'title' => 'dasar',
            'description' => 'description'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/post');
    }

    public function testEditDataFailedPost()
    {
        $repo = Mockery::mock(MySQLPostRepository::class);

        app()->instance(PostRepository::class, $repo);
        $response = $this->put('/post/dasar', [
            '_token' => csrf_token(),
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    public function testDeleteDataSuccessfullyPost()
    {
        $repo = Mockery::mock(MySQLPostRepository::class);
        $repo->shouldReceive('deleteBySlug')->once();
        app()->instance(PostRepository::class, $repo);
        $response = $this->delete('/post/dasar');
        $response->assertStatus(302);
        $response->assertRedirect('/post');
    }
}
