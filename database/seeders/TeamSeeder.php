<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach (range(1, 5) as $i) {
            $team = Team::factory()->create([
                'owner_id' => $users->random()->id,
            ]);

            $teamMembers = $users->random(min($users->count(), fake()->numberBetween(2, 4)))->unique();
            foreach ($teamMembers as $member) {
                $team->members()->create([
                    'user_id' => $member->id,
                    'role' => fake()->randomElement(['owner', 'admin', 'member']),
                ]);
            }
        }
    }
}
