<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $admin = User::updateOrCreate(
            ['email' => 'admin@devflow.test'],
            ['name' => 'Admin User', 'password' => bcrypt('password')]
        );
        $admin->assignRole('Admin');

        $manager = User::updateOrCreate(
            ['email' => 'manager@devflow.test'],
            ['name' => 'Manager User', 'password' => bcrypt('password')]
        );
        $manager->assignRole('Manager');

        $employee = User::updateOrCreate(
            ['email' => 'employee@devflow.test'],
            ['name' => 'Employee User', 'password' => bcrypt('password')]
        );
        $employee->assignRole('Employee');

        $this->call([
            ProjectSeeder::class,
            TeamSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
