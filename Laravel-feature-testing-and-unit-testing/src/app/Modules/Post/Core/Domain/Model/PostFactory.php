<?php

namespace App\Modules\Post\Core\Domain\Model;

use App\Modules\Post\Core\Domain\Model\Post;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence();
        return [
            'title' => $title,
            'slug' => SlugService::createSlug(Post::class, 'slug', $title),
            'description' => $this->faker->paragraph(),
        ];
    }
}
