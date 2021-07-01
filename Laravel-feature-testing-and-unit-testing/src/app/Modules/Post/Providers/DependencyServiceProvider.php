<?php

namespace App\Modules\Post\Providers;

use App\Modules\Post\Core\Application\Service\Contract\PostService;
use App\Modules\Post\Core\Application\Service\PostServiceImpl;
use App\Modules\Post\Core\Domain\Repository\PostRepository;
use App\Modules\Post\Infrastructure\Repository\MySQLPostRepository;
use Illuminate\Support\ServiceProvider;

class DependencyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PostRepository::class, MySQLPostRepository::class);
    }
}
