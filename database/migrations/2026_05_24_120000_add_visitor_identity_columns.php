<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitor_events', function (Blueprint $table) {
            if (! Schema::hasColumn('visitor_events', 'visitor_id_hash')) {
                $table->string('visitor_id_hash', 64)->nullable()->after('ip_hash')->index();
            }
            if (! Schema::hasColumn('visitor_events', 'host')) {
                $table->string('host', 200)->nullable()->after('path')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('visitor_events', function (Blueprint $table) {
            if (Schema::hasColumn('visitor_events', 'visitor_id_hash')) {
                $table->dropColumn('visitor_id_hash');
            }
            if (Schema::hasColumn('visitor_events', 'host')) {
                $table->dropColumn('host');
            }
        });
    }
};
