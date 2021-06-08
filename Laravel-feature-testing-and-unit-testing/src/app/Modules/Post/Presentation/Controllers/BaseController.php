<?php

namespace App\Modules\Post\Presentation\Controllers;

use App\Modules\Post\Core\Application\Service\Contract\PostService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        $message = __('Post::general.belajar');
        $posts = $this->postService->getAllData();
        return view('Post::welcome', compact('message'));
    }
}
