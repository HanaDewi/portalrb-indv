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
        Schema::table('general_rencana_aksi_output', function(Blueprint $table) {
            $table->dropColumn('capaian_anggaran');
            $table->after('realisasi_anggaran_total', function(Blueprint $table){
                $table->string('capaian_output_tw1')->nullable();
                $table->string('capaian_output_tw2')->nullable();
                $table->string('capaian_output_tw3')->nullable();
                $table->string('capaian_output_tw4')->nullable();
                $table->string('capaian_output_total')->nullable();
                $table->string('capaian_anggaran_tw1')->nullable();
                $table->string('capaian_anggaran_tw2')->nullable();
                $table->string('capaian_anggaran_tw3')->nullable();
                $table->string('capaian_anggaran_tw4')->nullable();
                $table->string('capaian_anggaran_total')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_rencana_aksi_output', function(Blueprint $table) {
            $table->string('capaian_anggaran')->nullable();
            $table->dropColumn('capaian_output_tw1');
            $table->dropColumn('capaian_output_tw2');
            $table->dropColumn('capaian_output_tw3');
            $table->dropColumn('capaian_output_tw4');
            $table->dropColumn('capaian_output_total');
            $table->dropColumn('capaian_anggaran_tw1');
            $table->dropColumn('capaian_anggaran_tw2');
            $table->dropColumn('capaian_anggaran_tw3');
            $table->dropColumn('capaian_anggaran_tw4');
            $table->dropColumn('capaian_anggaran_total');
        });
    }
};
