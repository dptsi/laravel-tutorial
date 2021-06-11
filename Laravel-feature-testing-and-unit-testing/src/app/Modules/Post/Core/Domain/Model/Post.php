<?php

namespace App\Modules\Post\Core\Domain\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
  use HasFactory;
  use Sluggable;

  protected $fillable = [
    'title', 'slug', 'description', 'image_path'
  ];

  public function sluggable(): array
  {
      return [
          'slug' => [
              'source' => 'title'
          ]
      ];
  }
}
