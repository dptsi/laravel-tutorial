<?php

namespace App\Modules\Post\Infrastructure\Repository;

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
    $posts = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $posts;
  }
}
