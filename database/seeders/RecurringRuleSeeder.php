<?php

namespace Database\Seeders;

use App\Models\RecurringRule;
use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Database\Seeder;

class RecurringRuleSeeder extends Seeder
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

        $bankAccount = $user->accounts()->where('name', 'Main Bank')->first();
        $billsCategory = $user->categories()->where('name', 'Bills & Utilities')->first();

        $recurringRule = $user->recurringRules()->create([
            'name' => 'Monthly Internet Bill',
            'type' => 'expense',
            'amount' => 500000,
            'frequency' => 'monthly',
            'day_of_month' => 15,
            'start_date' => now()->subMonths(2),
            'default_account_id' => $bankAccount->id,
            'default_category_id' => $billsCategory->id,
            'note' => 'Internet service provider monthly bill',
            'is_active' => true,
        ]);

        $this->command->info("Recurring rule created: {$recurringRule->name}");
    }
}
