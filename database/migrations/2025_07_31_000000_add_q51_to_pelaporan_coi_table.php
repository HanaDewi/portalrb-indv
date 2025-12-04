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
        Schema::table('pelaporan_coi', function (Blueprint $table) {
            $table->string('q51_url_sistem')->nullable()->after('q5_sistem_aplikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelaporan_coi', function (Blueprint $table) {
            $table->dropColumn('q51_url_sistem');
        });
    }
};
