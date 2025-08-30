<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
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
            'name' => $this->faker->randomElement(['Food', 'Transport', 'Bills', 'Shopping', 'Entertainment']),
            'type' => $this->faker->randomElement(['income', 'expense']),
            'color' => $this->faker->hexColor(),
            'icon' => $this->faker->randomElement(['🍕', '🚗', '💡', '🛍️', '🎬']),
            'parent_id' => null,
            'is_archived' => false,
        ];
    }
}
