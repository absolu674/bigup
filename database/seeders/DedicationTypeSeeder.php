<?php

namespace Database\Seeders;

use App\Models\DedicationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DedicationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dedications = [
            ['name' => 'Vidéo Personnalisée', 'description' => 'Une vidéo enregistrée avec un message personnel'],
            ['name' => 'Message Audio', 'description' => 'Un message vocal personnalisé'],
            ['name' => 'Écrit', 'description' => 'Un message écrit signé par l’artiste'],
        ];

        foreach ($dedications as $dedication) {
            DedicationType::create($dedication);
        }
    }
}
