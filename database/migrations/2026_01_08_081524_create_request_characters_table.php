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
        Schema::create('request_characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->onDelete('cascade');
            $table->string('name', 100);
            $table->timestamps();

            // İndeksler
            $table->index('request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_characters');
    }
};
