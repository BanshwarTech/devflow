<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = \App\Models\Activity::class;

    public function definition(): array
    {
        return [
            'description' => fake()->sentence(),
            'causer_id' => fake()->optional()->biasedElement(User::all(), ['id']),
            'properties' => fake()->optional()->randomElements(['key' => fake()->word(), 'value' => fake()->word()], 2),
        ];
    }
}
