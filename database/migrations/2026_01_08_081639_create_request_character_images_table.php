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
        Schema::create('request_character_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained('request_characters')->onDelete('cascade');
            $table->string('image_path');
            $table->tinyInteger('order')->default(1);
            $table->timestamps();

            // İndeksler
            $table->index('character_id');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_character_images');
    }
};
