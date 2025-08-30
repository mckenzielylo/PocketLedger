<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
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

        $cashAccount = $user->accounts()->where('name', 'Cash')->first();
        $bankAccount = $user->accounts()->where('name', 'Main Bank')->first();
        
        $salaryCategory = $user->categories()->where('name', 'Salary')->first();
        $foodCategory = $user->categories()->where('name', 'Food & Dining')->first();
        $transportCategory = $user->categories()->where('name', 'Transportation')->first();

        $transactions = [
            [
                'type' => 'income',
                'amount' => 8000000,
                'occurred_on' => now()->subDays(5),
                'payee' => 'Company XYZ',
                'note' => 'Monthly salary',
                'account_id' => $bankAccount->id,
                'category_id' => $salaryCategory->id,
            ],
            [
                'type' => 'expense',
                'amount' => 150000,
                'occurred_on' => now()->subDays(3),
                'payee' => 'Restaurant ABC',
                'note' => 'Lunch with colleagues',
                'account_id' => $cashAccount->id,
                'category_id' => $foodCategory->id,
            ],
            [
                'type' => 'expense',
                'amount' => 50000,
                'occurred_on' => now()->subDays(2),
                'payee' => 'GoJek',
                'note' => 'Transport to meeting',
                'account_id' => $cashAccount->id,
                'category_id' => $transportCategory->id,
            ],
            [
                'type' => 'transfer',
                'amount' => 1000000,
                'occurred_on' => now()->subDays(1),
                'payee' => 'Transfer to Cash',
                'note' => 'Withdrawal for daily expenses',
                'account_id' => $bankAccount->id,
                'transfer_account_id' => $cashAccount->id,
            ],
        ];

        foreach ($transactions as $transactionData) {
            $transaction = $user->transactions()->create($transactionData);
            $this->command->info("Transaction created: {$transaction->type} - {$transaction->amount}");
        }

        // Update account balances
        $cashAccount->updateBalance();
        $bankAccount->updateBalance();
    }
}
