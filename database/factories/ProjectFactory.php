<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(['planning', 'active', 'on_hold', 'completed', 'cancelled']),
            'start_date' => fake()->dateTimeBetween('-3 months', 'now'),
            'end_date' => fake()->optional()->dateTimeBetween('now', '+6 months'),
            'user_id' => User::factory(),
        ];
    }
}
