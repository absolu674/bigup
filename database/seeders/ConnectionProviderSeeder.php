<?php

namespace Database\Seeders;

use App\Models\ConnectionProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConnectionProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = ['basic', 'facebook', 'gmail'];

        foreach ($providers as $provider) {
            ConnectionProvider::create(['name' => $provider]);
        }
    }
}
