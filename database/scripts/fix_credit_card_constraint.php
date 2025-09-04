<?php

/**
 * Script to fix the credit card constraint on production database
 * This can be run via artisan command or directly
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

echo "🔧 Fixing credit card constraint on production database...\n";

try {
    // Check if we're using PostgreSQL
    if (config('database.default') === 'pgsql') {
        echo "📊 Detected PostgreSQL database\n";
        
        // Check current constraint
        $constraints = DB::select("
            SELECT conname, pg_get_constraintdef(oid) as definition 
            FROM pg_constraint 
            WHERE conrelid = 'accounts'::regclass AND contype = 'c'
        ");
        
        echo "🔍 Current constraints:\n";
        foreach ($constraints as $constraint) {
            echo "  - {$constraint->conname}: {$constraint->definition}\n";
        }
        
        // Drop existing constraint
        echo "🗑️ Dropping existing constraint...\n";
        DB::statement("ALTER TABLE accounts DROP CONSTRAINT IF EXISTS accounts_type_check");
        
        // Add new constraint with credit-card
        echo "➕ Adding new constraint with credit-card support...\n";
        DB::statement("ALTER TABLE accounts ADD CONSTRAINT accounts_type_check CHECK (type IN ('cash', 'bank', 'e-wallet', 'credit-card'))");
        
        echo "✅ Constraint updated successfully!\n";
        
        // Test the constraint
        echo "🧪 Testing constraint...\n";
        $testResult = DB::select("SELECT 1 FROM accounts WHERE type = 'credit-card' LIMIT 0");
        echo "✅ Constraint test passed!\n";
        
    } else {
        echo "❌ This script is designed for PostgreSQL only\n";
        exit(1);
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    Log::error('Credit card constraint fix failed: ' . $e->getMessage());
    exit(1);
}

echo "🎉 Credit card constraint fix completed successfully!\n";
