<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Permission::findOrCreate('manage projects');
        Permission::findOrCreate('manage tasks');
        Permission::findOrCreate('manage teams');
        Permission::findOrCreate('view dashboard');
        Permission::findOrCreate('manage users');

        $admin = Role::findOrCreate('Admin');
        $admin->syncPermissions(Permission::all());

        $manager = Role::findOrCreate('Manager');
        $manager->syncPermissions(['manage projects', 'manage tasks', 'manage teams', 'view dashboard']);

        $employee = Role::findOrCreate('Employee');
        $employee->syncPermissions(['view dashboard']);
    }
}
