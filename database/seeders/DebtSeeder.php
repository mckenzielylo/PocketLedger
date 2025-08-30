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
            'lender' => 'Bank ABC',
            'principal' => 50000000,
            'interest_rate' => 8.5,
            'min_payment' => 1500000,
            'due_day' => 25,
            'current_balance' => 50000000,
            'account_id' => $bankAccount->id,
            'opened_on' => now()->subMonths(6),
            'note' => '5-year car loan for new vehicle',
        ]);

        $this->command->info("Debt created: {$debt->name} - {$debt->principal}");
    }
}
