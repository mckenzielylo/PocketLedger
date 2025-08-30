<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recurring_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['income', 'expense', 'debt_payment']);
            $table->decimal('amount', 14, 2);
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'yearly']);
            $table->tinyInteger('day_of_month')->nullable(); // 1-31
            $table->tinyInteger('weekday')->nullable(); // 0-6 (Sunday = 0)
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignId('default_account_id')->constrained('accounts')->onDelete('cascade');
            $table->foreignId('default_category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->text('note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['user_id', 'is_active', 'start_date']);
            $table->index(['user_id', 'frequency', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_rules');
    }
};
