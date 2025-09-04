<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {--name=} {--email=} {--password=} {--currency=USD}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->option('name') ?: $this->ask('What is the user\'s name?');
        $email = $this->option('email') ?: $this->ask('What is the user\'s email?');
        $password = $this->option('password') ?: $this->secret('What is the user\'s password?');
        $currency = $this->option('currency') ?: $this->choice('What is the user\'s preferred currency?', ['USD', 'EUR', 'GBP', 'IDR', 'JPY'], 'USD');

        // Validate input
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('- ' . $error);
            }
            return 1;
        }

        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make($password),
            'settings' => [
                'currency' => $currency,
                'first_day_of_week' => 1, // Monday
                'locale' => 'en',
                'dark_mode' => false,
            ],
        ]);

        $this->info("User created successfully!");
        $this->line("Name: {$user->name}");
        $this->line("Email: {$user->email}");
        $this->line("Password: {$password}");
        $this->line("Currency: {$currency}");

        return 0;
    }
}
