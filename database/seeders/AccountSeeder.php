<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
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

        $accounts = [
            [
                'name' => 'Cash',
                'type' => 'cash',
                'currency' => 'IDR',
                'starting_balance' => 1000000,
                'current_balance' => 1000000,
            ],
            [
                'name' => 'Main Bank',
                'type' => 'bank',
                'currency' => 'IDR',
                'starting_balance' => 5000000,
                'current_balance' => 5000000,
            ],
            [
                'name' => 'Credit Card',
                'type' => 'credit-card',
                'currency' => 'IDR',
                'starting_balance' => 0,
                'current_balance' => 0,
            ],
        ];

        foreach ($accounts as $accountData) {
            $account = $user->accounts()->create($accountData);
            $this->command->info("Account created: {$account->name}");
        }

        // Set the first account as default
        $user->setSetting('default_account_id', $user->accounts()->first()->id);
    }
}
