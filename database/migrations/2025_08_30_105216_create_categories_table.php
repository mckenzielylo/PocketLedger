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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['income', 'expense']);
            $table->string('color', 7)->default('#3B82F6');
            $table->string('icon')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->boolean('is_archived')->default(false);
            $table->timestamps();
            
            $table->index(['user_id', 'type', 'is_archived']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
