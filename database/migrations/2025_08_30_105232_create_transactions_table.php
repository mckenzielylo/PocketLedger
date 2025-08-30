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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['income', 'expense', 'transfer']);
            $table->decimal('amount', 14, 2);
            $table->date('occurred_on');
            $table->string('payee')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('transfer_account_id')->nullable()->constrained('accounts')->onDelete('set null');
            $table->string('receipt_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'account_id', 'occurred_on']);
            $table->index(['user_id', 'type', 'occurred_on']);
            $table->index(['user_id', 'category_id', 'occurred_on']);
            
            // Note: Business logic constraint: if type='transfer', category_id must be null and transfer_account_id required
            // This will be enforced in the application layer
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
