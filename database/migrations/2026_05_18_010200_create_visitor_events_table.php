<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_events', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 64)->index();
            $table->string('path', 500);
            $table->string('referer', 500)->nullable();
            $table->string('referrer_host', 200)->nullable()->index();
            $table->string('source', 30)->nullable()->index(); // 'direct' | 'organic' | 'social' | 'referral'
            $table->string('user_agent_hash', 64)->index();
            $table->string('ip_hash', 64)->index();
            $table->boolean('is_new_session')->default(false)->index();
            $table->boolean('is_new_visitor')->default(false)->index();
            $table->unsignedInteger('session_event_index')->default(1);
            $table->timestamp('created_at')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_events');
    }
};
