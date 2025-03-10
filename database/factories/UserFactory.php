<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['artist', 'client']);
        $price = $type == 'artist' ?? $this->faker->randomFloat(2, 100, 500);
        return [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'gender' => $this->faker->randomElement(['woman', 'man']),
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->randomNumber(9),
            'professional_phone' => $this->faker->unique()->randomNumber(9),
            'dedication_price' => $price,
            'country' => $this->faker->country,
            'verified' => $this->faker->boolean,
            'password' => bcrypt('password'),
            'bio' => $type == 'artist' ? $this->faker->sentence : null,
            'type' => $type,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
