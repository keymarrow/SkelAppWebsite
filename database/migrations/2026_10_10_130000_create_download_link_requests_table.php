<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Record every "send me the download link via SMS" request from the
 * download modal, so the phone numbers people hand over are kept somewhere
 * reviewable in the CMS instead of only existing inside a Beem SMS log.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('download_link_requests', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 30);
            $table->string('country_code', 10)->nullable();
            $table->string('destination', 20);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('download_link_requests');
    }
};
