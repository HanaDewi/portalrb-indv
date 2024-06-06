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
        Schema::table('open_access_settings', function (Blueprint $table) {
            $table->after('user_level', function(Blueprint $table) {
                $table->string('fitur');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_rencana_aksi_output', function(Blueprint $table) {
            $table->dropColumn('fitur');
        });
    }
};
