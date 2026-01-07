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
        Schema::create('token_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['credit', 'debit']);
            $table->text('description');
            $table->unsignedBigInteger('order_id')->nullable(); // Foreign key constraint yok
            $table->decimal('balance_after', 10, 2);
            $table->timestamps();

            // Performans için indeksler
            $table->index('user_id');
            $table->index('type');
            $table->index('order_id'); // Index var ama constraint yok
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_transactions');
    }
};
