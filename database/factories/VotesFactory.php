<?php

namespace Database\Factories;

use App\Models\ArtistCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Votes>
 */
class VotesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::where('id', '!=', function ($query) {
                $query->select('id')->from('users')->inRandomOrder()->limit(1);
            })->inRandomOrder()->first()->id,
            'artist_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
