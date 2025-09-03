<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;

class MultiCurrencySampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the demo user
        $user = User::where('email', 'demo@pocketledger.com')->first();
        
        if (!$user) {
            $this->command->error('Demo user not found. Please run the main seeder first.');
            return;
        }

        $this->command->info('Creating multi-currency sample data...');

        // Create sample accounts in different currencies
        $accounts = [
            [
                'name' => 'USD Salary Account',
                'type' => 'bank',
                'currency' => 'USD',
                'starting_balance' => 5000.00,
                'current_balance' => 5000.00,
            ],
            [
                'name' => 'EUR Travel Fund',
                'type' => 'bank',
                'currency' => 'EUR',
                'starting_balance' => 2500.00,
                'current_balance' => 2500.00,
            ],
            [
                'name' => 'SGD Business Account',
                'type' => 'bank',
                'currency' => 'SGD',
                'starting_balance' => 8000.00,
                'current_balance' => 8000.00,
            ],
            [
                'name' => 'JPY Cash Wallet',
                'type' => 'cash',
                'currency' => 'JPY',
                'starting_balance' => 50000.00,
                'current_balance' => 50000.00,
            ],
            [
                'name' => 'AUD Savings',
                'type' => 'bank',
                'currency' => 'AUD',
                'starting_balance' => 3000.00,
                'current_balance' => 3000.00,
            ],
            [
                'name' => 'CAD Investment',
                'type' => 'bank',
                'currency' => 'CAD',
                'starting_balance' => 4000.00,
                'current_balance' => 4000.00,
            ],
            [
                'name' => 'HKD Trading Account',
                'type' => 'e-wallet',
                'currency' => 'HKD',
                'starting_balance' => 15000.00,
                'current_balance' => 15000.00,
            ],
            [
                'name' => 'KRW Crypto Wallet',
                'type' => 'e-wallet',
                'currency' => 'KRW',
                'starting_balance' => 2000000.00,
                'current_balance' => 2000000.00,
            ],
        ];

        $createdAccounts = [];
        foreach ($accounts as $accountData) {
            $account = $user->accounts()->create($accountData);
            $createdAccounts[] = $account;
            $this->command->info("Created {$account->name} ({$account->currency}) with balance {$account->currency} {$account->current_balance}");
        }

        // Create sample transactions for different currencies
        $this->createSampleTransactions($user, $createdAccounts);

        $this->command->info('Multi-currency sample data created successfully!');
    }

    private function createSampleTransactions($user, $accounts)
    {
        // Get or create categories
        $salaryCategory = $user->categories()->where('name', 'Salary')->first();
        $foodCategory = $user->categories()->where('name', 'Food & Dining')->first();
        $travelCategory = $user->categories()->where('name', 'Travel')->first();
        $shoppingCategory = $user->categories()->where('name', 'Shopping')->first();

        // Create Travel category if it doesn't exist
        if (!$travelCategory) {
            $travelCategory = $user->categories()->create([
                'name' => 'Travel',
                'type' => 'expense',
                'color' => '#3B82F6',
            ]);
            $this->command->info('Created Travel category');
        }

        if (!$salaryCategory || !$foodCategory || !$shoppingCategory) {
            $this->command->error('Required categories not found. Please run the main seeder first.');
            return;
        }

        $transactions = [
            // USD Salary Account transactions
            [
                'account_id' => $accounts[0]->id, // USD Salary Account
                'category_id' => $salaryCategory->id,
                'type' => 'income',
                'amount' => 3500.00,
                'occurred_on' => now()->subDays(5),
                'payee' => 'Tech Company Inc.',
                'note' => 'Monthly salary payment',
            ],
            [
                'account_id' => $accounts[0]->id, // USD Salary Account
                'category_id' => $foodCategory->id,
                'type' => 'expense',
                'amount' => 45.50,
                'occurred_on' => now()->subDays(3),
                'payee' => 'Starbucks',
                'note' => 'Coffee and breakfast',
            ],

            // EUR Travel Fund transactions
            [
                'account_id' => $accounts[1]->id, // EUR Travel Fund
                'category_id' => $travelCategory->id,
                'type' => 'expense',
                'amount' => 120.00,
                'occurred_on' => now()->subDays(7),
                'payee' => 'Airbnb',
                'note' => 'Hotel booking in Paris',
            ],
            [
                'account_id' => $accounts[1]->id, // EUR Travel Fund
                'category_id' => $foodCategory->id,
                'type' => 'expense',
                'amount' => 35.80,
                'occurred_on' => now()->subDays(2),
                'payee' => 'Restaurant Le Bistro',
                'note' => 'Dinner in Paris',
            ],

            // SGD Business Account transactions
            [
                'account_id' => $accounts[2]->id, // SGD Business Account
                'category_id' => $salaryCategory->id,
                'type' => 'income',
                'amount' => 2500.00,
                'occurred_on' => now()->subDays(10),
                'payee' => 'Freelance Client',
                'note' => 'Web development project',
            ],
            [
                'account_id' => $accounts[2]->id, // SGD Business Account
                'category_id' => $shoppingCategory->id,
                'type' => 'expense',
                'amount' => 89.90,
                'occurred_on' => now()->subDays(4),
                'payee' => 'Apple Store Singapore',
                'note' => 'iPhone accessories',
            ],

            // JPY Cash Wallet transactions
            [
                'account_id' => $accounts[3]->id, // JPY Cash Wallet
                'category_id' => $foodCategory->id,
                'type' => 'expense',
                'amount' => 1200.00,
                'occurred_on' => now()->subDays(6),
                'payee' => 'Sushi Restaurant',
                'note' => 'Lunch in Tokyo',
            ],
            [
                'account_id' => $accounts[3]->id, // JPY Cash Wallet
                'category_id' => $travelCategory->id,
                'type' => 'expense',
                'amount' => 500.00,
                'occurred_on' => now()->subDays(1),
                'payee' => 'Tokyo Metro',
                'note' => 'Train tickets',
            ],

            // AUD Savings transactions
            [
                'account_id' => $accounts[4]->id, // AUD Savings
                'category_id' => $salaryCategory->id,
                'type' => 'income',
                'amount' => 1800.00,
                'occurred_on' => now()->subDays(8),
                'payee' => 'Australian Client',
                'note' => 'Consulting work',
            ],

            // CAD Investment transactions
            [
                'account_id' => $accounts[5]->id, // CAD Investment
                'category_id' => $salaryCategory->id,
                'type' => 'income',
                'amount' => 2200.00,
                'occurred_on' => now()->subDays(12),
                'payee' => 'Canadian Company',
                'note' => 'Remote work payment',
            ],

            // HKD Trading Account transactions
            [
                'account_id' => $accounts[6]->id, // HKD Trading Account
                'category_id' => $shoppingCategory->id,
                'type' => 'expense',
                'amount' => 450.00,
                'occurred_on' => now()->subDays(9),
                'payee' => 'Hong Kong Electronics',
                'note' => 'Gadget purchase',
            ],

            // KRW Crypto Wallet transactions
            [
                'account_id' => $accounts[7]->id, // KRW Crypto Wallet
                'category_id' => $salaryCategory->id,
                'type' => 'income',
                'amount' => 500000.00,
                'occurred_on' => now()->subDays(15),
                'payee' => 'Crypto Trading',
                'note' => 'Bitcoin trading profit',
            ],
            [
                'account_id' => $accounts[7]->id, // KRW Crypto Wallet
                'category_id' => $foodCategory->id,
                'type' => 'expense',
                'amount' => 25000.00,
                'occurred_on' => now()->subDays(5),
                'payee' => 'Korean BBQ Restaurant',
                'note' => 'Dinner in Seoul',
            ],
        ];

        foreach ($transactions as $transactionData) {
            $transaction = $user->transactions()->create($transactionData);
            $this->command->info("Created transaction: {$transaction->type} {$transaction->amount} for {$transaction->payee}");
        }

        // Update account balances
        foreach ($accounts as $account) {
            $account->updateBalance();
            $this->command->info("Updated balance for {$account->name}: {$account->currency} {$account->current_balance}");
        }
    }
}