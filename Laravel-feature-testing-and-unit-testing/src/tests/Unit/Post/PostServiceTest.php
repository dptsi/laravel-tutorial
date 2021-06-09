<?php

namespace Tests\Unit\Post;

use App\Modules\Post\Core\Application\Service\PostServiceImpl;
use App\Modules\Post\Core\Domain\Model\Post;
use App\Modules\Post\Infrastructure\Repository\MySQLPostRepository;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class PostServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetAllDataAndReturnNull()
    {
        $repo = Mockery::mock(MySQLPostRepository::class);
        $repo->shouldReceive('getAll')->andReturnNull();
        $service = new PostServiceImpl($repo);
        $actual = $service->getAllData();
        $this->assertSame(null, $actual);
    }

    public function testGetAllDataAndReturnOneData()
    {
        $repo = Mockery::mock(MySQLPostRepository::class);
        $post = new Post(["name" => "Post", "description" => "Test Post"]);
        $post = $post->toArray();
        var_dump($post);
        $repo->shouldReceive('getAll')->andReturn($post);
        $service = new PostServiceImpl($repo);
        $actual = $service->getAllData();
        $expectedOutput = $post;
        $this->assertSame($expectedOutput, $actual);
    }

    public function testGetAllDataAndReturnManyPosts()
    {
        $repo = Mockery::mock(MySQLPostRepository::class);
        $posts = [
            (new Post(["name" => "Post", "description" => "Test Post"]))->toArray(),
            (new Post(["name" => "Post 1", "description" => "Test Post 1"]))->toArray(),

        ];
        var_dump($posts);
        $repo->shouldReceive('getAll')->andReturn($posts);
        $service = new PostServiceImpl($repo);
        $actual = $service->getAllData();
        $expectedOutput = $posts;
        $this->assertSame($expectedOutput, $actual);
    }
}
