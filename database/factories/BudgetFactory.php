<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Budget>
 */
class BudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = $this->faker->numberBetween(2020, 2030);
        $month = $this->faker->numberBetween(1, 12);
        $monthStr = sprintf('%04d-%02d', $year, $month);
        
        return [
            'user_id' => \App\Models\User::factory(),
            'month' => $monthStr,
            'total_limit' => $this->faker->randomFloat(2, 100000, 10000000),
        ];
    }
}
