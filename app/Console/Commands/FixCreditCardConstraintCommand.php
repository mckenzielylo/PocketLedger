<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixCreditCardConstraintCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:fix-credit-card-constraint';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the credit card constraint on the accounts table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Fixing credit card constraint on production database...');

        try {
            // Check if we're using PostgreSQL
            if (config('database.default') === 'pgsql') {
                $this->info('📊 Detected PostgreSQL database');
                
                // Check current constraint
                $constraints = DB::select("
                    SELECT conname, pg_get_constraintdef(oid) as definition 
                    FROM pg_constraint 
                    WHERE conrelid = 'accounts'::regclass AND contype = 'c'
                ");
                
                $this->info('🔍 Current constraints:');
                foreach ($constraints as $constraint) {
                    $this->line("  - {$constraint->conname}: {$constraint->definition}");
                }
                
                // Drop existing constraint
                $this->info('🗑️ Dropping existing constraint...');
                DB::statement("ALTER TABLE accounts DROP CONSTRAINT IF EXISTS accounts_type_check");
                
                // Add new constraint with credit-card
                $this->info('➕ Adding new constraint with credit-card support...');
                DB::statement("ALTER TABLE accounts ADD CONSTRAINT accounts_type_check CHECK (type IN ('cash', 'bank', 'e-wallet', 'credit-card'))");
                
                $this->info('✅ Constraint updated successfully!');
                
                // Test the constraint
                $this->info('🧪 Testing constraint...');
                $testResult = DB::select("SELECT 1 FROM accounts WHERE type = 'credit-card' LIMIT 0");
                $this->info('✅ Constraint test passed!');
                
            } else {
                $this->error('❌ This command is designed for PostgreSQL only');
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            Log::error('Credit card constraint fix failed: ' . $e->getMessage());
            return 1;
        }

        $this->info('🎉 Credit card constraint fix completed successfully!');
        return 0;
    }
}
