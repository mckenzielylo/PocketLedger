<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\ExchangeRateService;

class ExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding exchange rates...');
        
        $exchangeRateService = new ExchangeRateService();
        $success = $exchangeRateService->updateRates();
        
        if ($success) {
            $this->command->info('Exchange rates seeded successfully!');
        } else {
            $this->command->error('Failed to seed exchange rates.');
        }
    }
}