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
        // Note: We can't directly modify enum values in MySQL, so we'll use raw SQL
        DB::statement("ALTER TABLE accounts MODIFY COLUMN type ENUM('cash', 'bank', 'e-wallet', 'credit-card') NOT NULL");
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
