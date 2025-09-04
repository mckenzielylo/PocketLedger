<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo user
        $demoUser = User::create([
            'name' => 'Demo User',
            'email' => 'demo@pocketledger.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'settings' => [
                'currency' => 'IDR',
                'first_day_of_week' => 1, // Monday
                'locale' => 'en',
                'dark_mode' => false,
            ],
        ]);

        $this->command->info("Demo user created: {$demoUser->email} / password");

        // Create additional test users
        $testUsers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'password123',
                'settings' => [
                    'currency' => 'USD',
                    'first_day_of_week' => 0, // Sunday
                    'locale' => 'en',
                    'dark_mode' => false,
                ],
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => 'password123',
                'settings' => [
                    'currency' => 'EUR',
                    'first_day_of_week' => 1, // Monday
                    'locale' => 'en',
                    'dark_mode' => true,
                ],
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob@example.com',
                'password' => 'password123',
                'settings' => [
                    'currency' => 'GBP',
                    'first_day_of_week' => 1, // Monday
                    'locale' => 'en',
                    'dark_mode' => false,
                ],
            ],
        ];

        foreach ($testUsers as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make($userData['password']),
                'settings' => $userData['settings'],
            ]);

            $this->command->info("Test user created: {$user->email} / {$userData['password']}");
        }
    }
}
