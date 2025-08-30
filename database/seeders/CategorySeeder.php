<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->error('No user found. Please run UserSeeder first.');
            return;
        }

        $categories = [
            // Income categories
            [
                'name' => 'Salary',
                'type' => 'income',
                'color' => '#10B981',
                'icon' => '💰',
            ],
            [
                'name' => 'Other Income',
                'type' => 'income',
                'color' => '#3B82F6',
                'icon' => '📈',
            ],
            // Expense categories
            [
                'name' => 'Food & Dining',
                'type' => 'expense',
                'color' => '#F59E0B',
                'icon' => '🍽️',
            ],
            [
                'name' => 'Transportation',
                'type' => 'expense',
                'color' => '#8B5CF6',
                'icon' => '🚗',
            ],
            [
                'name' => 'Bills & Utilities',
                'type' => 'expense',
                'color' => '#EF4444',
                'icon' => '📱',
            ],
            [
                'name' => 'Shopping',
                'type' => 'expense',
                'color' => '#EC4899',
                'icon' => '🛍️',
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = $user->categories()->create($categoryData);
            $this->command->info("Category created: {$category->name} ({$category->type})");
        }
    }
}
