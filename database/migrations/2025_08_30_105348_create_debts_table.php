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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('amount', 14, 2);
            $table->enum('type', ['borrowed', 'lent']);
            $table->decimal('interest_rate', 5, 2)->nullable(); // Annual percentage
            $table->date('due_date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'due_date']);
            $table->index(['user_id', 'is_paid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
