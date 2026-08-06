<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();
        $users = User::all();

        $priorities = ['low', 'medium', 'high', 'urgent'];
        $statuses = ['pending', 'in_progress', 'review', 'completed', 'blocked'];

        foreach ($projects as $project) {
            Task::factory()->count(fake()->numberBetween(2, 4))->create([
                'project_id' => $project->id,
                'assigned_to' => $users->random()->id,
                'created_by' => $project->user_id,
            ]);
        }

        foreach (Task::all() as $task) {
            $task->update([
                'status' => fake()->randomElement($statuses),
                'priority' => fake()->randomElement($priorities),
                'due_date' => fake()->optional()->dateTimeBetween('-1 month', '+3 months'),
            ]);
        }
    }
}
