<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->json('draft_content')->nullable();
            $table->json('published_content')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('published_by_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('draft_updated_by_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
