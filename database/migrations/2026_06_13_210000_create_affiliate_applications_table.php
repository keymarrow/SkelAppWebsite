<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_applications', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email');
            $table->string('phone_country_code', 10);
            $table->string('phone_number', 30);
            $table->string('country', 120);
            $table->string('primary_promotional_method', 120);
            $table->string('hear_about_program', 120);
            $table->text('marketing_details')->nullable();
            $table->boolean('accepts_agreement')->default(false);
            $table->boolean('accepts_marketing')->default(false);
            $table->boolean('eligibility_confirmed')->default(false);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_applications');
    }
};
