<?php

namespace Database\Factories;

use App\Models\Pemilik;
use Illuminate\Database\Eloquent\Factories\Factory;

// 1. tambah library ini
use Illuminate\Support\Str;

class PemilikFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */

     // 2. kasih root directory supaya bisa akses di tinker
    // protected $model = Pemilik::class;
    protected $model = \App\Models\Pemilik::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name_pemilik' => $this->faker->name(),
            'created_at' => now(),
                'updated_at' => now(),
            ];
    }
}


// 3. run di php artisan tinker -> 
// \App\Models\Pemilik::factory()->create();
// \App\Models\Pemilik::factory()->count(5)->create();
        