<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the accounts table exists and has the type column
        if (Schema::hasTable('accounts') && Schema::hasColumn('accounts', 'type')) {
            try {
                // First, check what the current enum values are
                $result = DB::select("SHOW COLUMNS FROM accounts LIKE 'type'");
                if (!empty($result)) {
                    $type = $result[0]->Type;
                    
                    // Only modify if credit-card is not already in the enum
                    if (strpos($type, 'credit-card') === false) {
                        DB::statement("ALTER TABLE accounts MODIFY COLUMN type ENUM('cash', 'bank', 'e-wallet', 'credit-card') NOT NULL");
                    }
                }
            } catch (\Exception $e) {
                // Log the error but don't fail the migration
                \Log::warning('Failed to add credit-card to accounts type enum: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE accounts MODIFY COLUMN type ENUM('cash', 'bank', 'e-wallet') NOT NULL");
    }
};
