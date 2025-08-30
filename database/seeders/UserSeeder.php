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
        $user = User::create([
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

        $this->command->info("Demo user created: {$user->email} / password");
    }
}
