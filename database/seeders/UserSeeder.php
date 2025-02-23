<?php

namespace Database\Seeders;

use App\Models\ConnectionProvider;
use App\Models\User;
use App\Models\UserConnectionProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory(10)->create()->each(function ($user) {
        //     $user->provider = ConnectionProvider::inRandomOrder()->first()->name;
        // });
        User::factory(10)->create()->each(function ($user){
            $user->type == 'client' ? $user->assignRole('client') : $user->assignRole('artist');
        });
    }
}
