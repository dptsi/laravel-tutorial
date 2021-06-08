<?php

namespace App\Modules\Post\Core\Application\Service;

use App\Modules\Post\Core\Application\Service\Contract\PostService;
use App\Modules\Post\Core\Domain\Repository\PostRepository;

class PostServiceImpl implements PostService
{
  private $postRepository;

  public function __construct(PostRepository $postRepository)
  {
    $this->postRepository = $postRepository;
  }

  public function getAllData()
  {
    return $this->postRepository->getAll();
  }
}
