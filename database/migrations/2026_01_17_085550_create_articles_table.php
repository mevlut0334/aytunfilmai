<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('article_categories')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // İçerik
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable(); // Kısa özet
            $table->longText('content');

            // Görsel
            $table->string('featured_image')->nullable();
            $table->string('image_alt')->nullable(); // SEO için alt text

            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            // Yayın Ayarları
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();

            // İstatistik
            $table->integer('views')->default(0);

            // Sıralama
            $table->integer('order')->default(0);

            $table->timestamps();

            // Index'ler (Performans için)
            $table->index('slug');
            $table->index('status');
            $table->index('published_at');
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
