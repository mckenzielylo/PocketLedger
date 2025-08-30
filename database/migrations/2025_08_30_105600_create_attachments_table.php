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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('attachable_type');
            $table->unsignedBigInteger('attachable_id');
            $table->string('path');
            $table->string('mime');
            $table->unsignedInteger('size');
            $table->timestamp('uploaded_at');
            $table->timestamps();
            
            $table->index(['attachable_type', 'attachable_id']);
            $table->index(['user_id', 'attachable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
