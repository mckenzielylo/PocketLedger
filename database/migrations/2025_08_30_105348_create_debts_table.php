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
            $table->string('lender')->nullable();
            $table->decimal('principal', 14, 2);
            $table->decimal('interest_rate', 5, 2)->nullable(); // Annual percentage
            $table->decimal('min_payment', 14, 2);
            $table->tinyInteger('due_day'); // 1-28
            $table->decimal('current_balance', 14, 2);
            $table->foreignId('account_id')->nullable()->constrained()->onDelete('set null');
            $table->date('opened_on');
            $table->date('closed_on')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'due_day']);
            $table->index(['user_id', 'closed_on']);
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
