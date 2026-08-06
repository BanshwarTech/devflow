<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = \App\Models\Task::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(['pending', 'in_progress', 'review', 'completed', 'blocked']),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+3 months'),
            'project_id' => Project::factory(),
            'assigned_to' => fake()->optional()->randomElement(User::all())?->id,
            'created_by' => User::factory(),
        ];
    }
}
