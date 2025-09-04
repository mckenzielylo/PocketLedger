<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->randomElement(['Cash', 'Bank Account', 'Credit Card', 'Savings']),
            'type' => $this->faker->randomElement(['cash', 'bank', 'e-wallet', 'credit-card']),
            'currency' => 'IDR',
            'starting_balance' => $this->faker->numberBetween(0, 10000000),
            'current_balance' => $this->faker->numberBetween(0, 10000000),
            'is_archived' => false,
        ];
    }
}
