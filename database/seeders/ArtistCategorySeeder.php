<?php

namespace Database\Seeders;

use App\Models\ArtistCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArtistCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Musicien', 'description' => 'Chanteurs, compositeurs, instrumentistes'],
            ['name' => 'Comédien', 'description' => 'Acteurs et humoristes'],
            ['name' => 'Peintre', 'description' => 'Artistes visuels et peintres'],
        ];

        foreach ($categories as $category) {
            ArtistCategory::create($category);
        }
    }
}
