<?php

namespace App\Modules\Post\Infrastructure\Repository;

use App\Modules\Post\Core\Domain\Model\Post;
use App\Modules\Post\Core\Domain\Repository\PostRepository;
use Illuminate\Support\Facades\DB;
use PDO;

class MySQLPostRepository implements PostRepository
{
  public function getAll()
  {
    $pdo = DB::getPdo();
    $query = 'select * from posts';
    $statement = $pdo->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $posts = [];
    foreach ($result as $row) {
      $post = $this->mapArrayToPost($row);
      array_push($posts, $post);
    }
    return $posts;
  }

  public function store($post)
  {
    $pdo = DB::getPdo();
    $query = "INSERT INTO posts (title, description, slug) VALUES
    (:title, :description, :slug)";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':title', $post['title']);
    $statement->bindParam(':description', $post['description']);
    $statement->bindParam(':slug', $post['slug']);
    $statement->execute();
  }

  public function update($oldSlug, $post)
  {
    $pdo = DB::getPdo();
    $query = "UPDATE posts 
      SET title = :title, description = :description, slug = :slug
      WHERE slug = :old_slug";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':title', $post['title']);
    $statement->bindParam(':description', $post['description']);
    $statement->bindParam(':slug', $post['slug']);
    $statement->bindParam(':old_slug', $oldSlug);
    $statement->execute();
  }

  public function deleteBySlug($slug)
  {
    $pdo = DB::getPdo();
    $query = "DELETE posts WHERE slug = :slug";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':slug', $slug);
    $statement->execute();
  }

  public function findBySlug($slug)
  {
    $pdo = DB::getPdo();
    $query = "select * from posts where slug = '$slug'";
    $statement = $pdo->prepare($query);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    if ($result) {
      $post = $this->mapArrayToPost($result);
    }
    return $post;
  }

  private function mapArrayToPost($data): Post
  {
    $post = new Post();
    $post->id = $data['id'];
    $post->title = $data['title'];
    $post->description = $data['description'];
    $post->createdAt = $data['created_at'];
    $post->slug = $data['slug'];
    $post->updatedAt = $data['updated_at'];
    return $post;
  }
}
