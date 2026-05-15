<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary');
            $table->longText('body_markdown');
            $table->json('categories');
            $table->string('card_label', 50)->default('News');
            $table->string('card_color_start', 10)->default('#eaf7ff');
            $table->string('card_color_mid', 10)->default('#7fc8ff');
            $table->string('card_color_end', 10)->default('#6e82ff');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('read_time_override', 20)->nullable();
            $table->boolean('is_published')->default(false)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_posts');
    }
};
