<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\BudgetCategory;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
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

        $currentMonth = now()->format('Y-m');
        
        $budget = $user->budgets()->create([
            'month' => $currentMonth,
            'total_limit' => 3000000, // 3 million IDR
        ]);

        $this->command->info("Budget created for {$currentMonth}: {$budget->total_limit}");

        // Create budget categories
        $expenseCategories = $user->categories()->where('type', 'expense')->get();
        
        $budgetLimits = [
            'Food & Dining' => 800000,
            'Transportation' => 500000,
            'Bills & Utilities' => 1000000,
            'Shopping' => 700000,
        ];

        foreach ($expenseCategories as $category) {
            $limit = $budgetLimits[$category->name] ?? 200000;
            
            $budgetCategory = $budget->budgetCategories()->create([
                'category_id' => $category->id,
                'limit_amount' => $limit,
            ]);

            $this->command->info("Budget category created: {$category->name} - {$limit}");
        }
    }
}
