<?php

namespace Database\Seeders;

use App\Models\ActivityLogs;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActivityLogs::factory(15)->create([
            'utilisateur_id' => User::inRandomOrder()->first()->id,
        ]);
    }
}
