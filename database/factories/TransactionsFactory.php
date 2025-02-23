<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transactions>
 */
class TransactionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dedication_id' => \App\Models\Dedication::factory(),
            'phone_payment' => $this->faker->phoneNumber,
            'amount' => $this->faker->randomFloat(0, 10, 1000),
            'status' => $this->faker->randomElement(TransactionStatus::values()),
            'mode_paiement' => $this->faker->randomElement(PaymentMethod::values()),
            'transaction_ref' => Str::uuid(),
            'bill_id' => $this->faker->randomFloat(0, 555000000012, 5559999999912),
        ];
    }
}
