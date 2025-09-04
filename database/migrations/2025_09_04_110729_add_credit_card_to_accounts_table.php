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
                // For PostgreSQL, we need to update the check constraint
                if (config('database.default') === 'pgsql') {
                    // Drop the existing constraint
                    DB::statement("ALTER TABLE accounts DROP CONSTRAINT IF EXISTS accounts_type_check");
                    // Add the new constraint with credit-card
                    DB::statement("ALTER TABLE accounts ADD CONSTRAINT accounts_type_check CHECK (type IN ('cash', 'bank', 'e-wallet', 'credit-card'))");
                } else {
                    // For MySQL, use the original enum approach
                    $result = DB::select("SHOW COLUMNS FROM accounts LIKE 'type'");
                    if (!empty($result)) {
                        $type = $result[0]->Type;
                        
                        // Only modify if credit-card is not already in the enum
                        if (strpos($type, 'credit-card') === false) {
                            DB::statement("ALTER TABLE accounts MODIFY COLUMN type ENUM('cash', 'bank', 'e-wallet', 'credit-card') NOT NULL");
                        }
                    }
                }
            } catch (\Exception $e) {
                // Log the error but don't fail the migration
                \Log::warning('Failed to add credit-card to accounts type constraint: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            if (config('database.default') === 'pgsql') {
                // For PostgreSQL, update the check constraint to remove credit-card
                DB::statement("ALTER TABLE accounts DROP CONSTRAINT IF EXISTS accounts_type_check");
                DB::statement("ALTER TABLE accounts ADD CONSTRAINT accounts_type_check CHECK (type IN ('cash', 'bank', 'e-wallet'))");
            } else {
                // For MySQL, use the original enum approach
                DB::statement("ALTER TABLE accounts MODIFY COLUMN type ENUM('cash', 'bank', 'e-wallet') NOT NULL");
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to remove credit-card from accounts type constraint: ' . $e->getMessage());
        }
    }
};
