<?php

namespace Database\Seeders;

use App\Models\Votes;
use Illuminate\Database\Seeder;

class VotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Votes::factory(20)->create();
    }
}
