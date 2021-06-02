<?php

namespace Database\Factories;

use App\Models\Montir;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MontirFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Montir::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name_montir' => $this->faker->name(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
