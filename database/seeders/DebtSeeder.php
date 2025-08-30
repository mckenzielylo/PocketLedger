<?php

namespace Database\Seeders;

use App\Models\Debt;
use App\Models\User;
use App\Models\Account;
use Illuminate\Database\Seeder;

class DebtSeeder extends Seeder
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

        $debt = $user->debts()->create([
            'name' => 'Car Loan',
            'amount' => 50000000,
            'type' => 'borrowed',
            'interest_rate' => 8.5,
            'due_date' => now()->addMonths(6),
            'description' => '5-year car loan for new vehicle',
            'is_paid' => false,
            'account_id' => $bankAccount->id,
        ]);

        $this->command->info("Debt created: {$debt->name} - IDR " . number_format($debt->amount, 0, ',', '.'));
    }
}
