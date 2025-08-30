<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
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

        $assets = [
            [
                'name' => 'Investment Portfolio',
                'category' => 'investment',
                'purchase_value' => 10000000,
                'current_value' => 11500000,
                'purchase_date' => now()->subMonths(12),
                'depreciation_method' => 'none',
                'notes' => 'Diversified stock and bond portfolio',
            ],
            [
                'name' => 'MacBook Pro',
                'category' => 'gadget',
                'purchase_value' => 25000000,
                'current_value' => 20000000,
                'purchase_date' => now()->subMonths(18),
                'depreciation_method' => 'straight_line',
                'notes' => 'Work laptop, depreciating over 3 years',
            ],
        ];

        foreach ($assets as $assetData) {
            $asset = $user->assets()->create($assetData);
            $this->command->info("Asset created: {$asset->name} - {$asset->current_value}");
        }
    }
}
