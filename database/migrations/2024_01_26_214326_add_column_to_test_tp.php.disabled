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
        Schema::table('lke_test_tp', function (Blueprint $table) {
            $table->float('rb_tematik')->after('lke_instansi_id')->nullable();
            $table->float('rb_general')->after('lke_instansi_id')->nullable();
            $table->float('index_rb_penyesuaian')->after('index_rb')->nullable();
            $table->float('rb_general_penyesuaian')->after('index_rb')->nullable();
            $table->integer('bobot_rb_general_penyesuaian')->after('index_rb')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lke_test_tp', function (Blueprint $table) {
            $table->dropColumn('rb_general');
            $table->dropColumn('rb_tematik');
            $table->dropColumn('rb_general_penyesuaian');
            $table->dropColumn('bobot_rb_general_penyesuaian');
            $table->dropColumn('index_rb_penyesuaian');
        });
    }
};
