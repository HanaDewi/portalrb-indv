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
        Schema::table('lke_parameter', function (Blueprint $table) {
            $table->dropColumn('tahun');
            $table->after('level', function(Blueprint $table) {
                $table->foreignId('lke_kegiatan_id');
            });
            $table->after('parent_id', function(Blueprint $table) {
                $table->foreignId('indikator_pengali_id')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lke_parameter', function (Blueprint $table) {
            $table->dropColumn('lke_kegiatan_id');
            $table->dropColumn('indikator_pengali_id');
            $table->after('level', function(Blueprint $table) {
                $table->smallInteger('tahun');
            });
        });
    }
};
