<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin'],
            ['name' => 'client'],
            ['name' => 'artist'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}
