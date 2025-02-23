<?php

namespace Database\Seeders;

use App\Models\Dedication;
use Illuminate\Database\Seeder;

class DedicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dedication::factory(20)->create();
    }
}
