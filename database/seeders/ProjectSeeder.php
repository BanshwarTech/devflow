<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $statuses = ['planning', 'active', 'on_hold', 'completed', 'cancelled'];

        foreach ($statuses as $status) {
            Project::factory()->count(2)->create([
                'status' => $status,
                'user_id' => $users->random()->id,
                'start_date' => fake()->dateTimeBetween('-3 months', 'now'),
                'end_date' => fake()->optional()->dateTimeBetween('now', '+6 months'),
            ]);
        }
    }
}
