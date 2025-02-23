<?php

namespace Database\Factories;

use App\Enums\DedicationState;
use App\Models\Artist;
use App\Models\Client;
use App\Models\DedicationType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DedicationType>
 */
class DedicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $state = $this->faker->randomElement(DedicationState::values());
        return [
            'dedication_type_id' => DedicationType::inRandomOrder()->first()->id,
            'artist_id' => Artist::inRandomOrder()->first()->id,
            'user_id' => Client::inRandomOrder()->first()->id,
            'message' => $this->faker->sentence,
            'message_rejected' => $state == DedicationState::REJECTED->value ?? $this->faker->sentence,
            'dedication_video_path' => 'https://www.w3schools.com/html/mov_bbb.mp4',
        ];
    }
}
