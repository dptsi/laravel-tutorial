<?php

namespace App\Modules\Post\Core\Domain\Repository;

use App\Modules\Post\Core\Domain\Model\Post;

interface PostRepository
{
  public function getAll();
  public function store($post);
  public function update($oldSlug, $post);
  public function deleteBySlug($slug);
  public function findBySlug($slug);
}
