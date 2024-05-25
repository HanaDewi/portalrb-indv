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
        Schema::table('general_rencana_aksi_output', function (Blueprint $table) {
            $table->decimal('target_tw1', 12, 2)->nullable()->change();
            $table->decimal('target_tw2', 12, 2)->nullable()->change();
            $table->decimal('target_tw3', 12, 2)->nullable()->change();
            $table->decimal('target_tw4', 12, 2)->nullable()->change();
            $table->decimal('target_total', 12, 2)->nullable()->change();
            $table->bigInteger('anggaran_total')->nullable()->change();
            $table->decimal('realisasi_output_tw1', 12, 2)->nullable()->change();
            $table->decimal('realisasi_output_tw2', 12, 2)->nullable()->change();
            $table->decimal('realisasi_output_tw3', 12, 2)->nullable()->change();
            $table->decimal('realisasi_output_tw4', 12, 2)->nullable()->change();
            $table->decimal('realisasi_output_total', 12, 2)->nullable()->change();
            $table->bigInteger('realisasi_anggaran_total')->nullable()->change();
        });

        Schema::table('tematik_rencana_aksi_output', function (Blueprint $table) {
            $table->decimal('target_tw1', 12, 2)->nullable()->change();
            $table->decimal('target_tw2', 12, 2)->nullable()->change();
            $table->decimal('target_tw3', 12, 2)->nullable()->change();
            $table->decimal('target_tw4', 12, 2)->nullable()->change();
            $table->decimal('target_total', 12, 2)->nullable()->change();
            $table->bigInteger('anggaran_total')->nullable()->change();
            $table->decimal('realisasi_output_tw1', 12, 2)->nullable()->change();
            $table->decimal('realisasi_output_tw2', 12, 2)->nullable()->change();
            $table->decimal('realisasi_output_tw3', 12, 2)->nullable()->change();
            $table->decimal('realisasi_output_tw4', 12, 2)->nullable()->change();
            $table->decimal('realisasi_output_total', 12, 2)->nullable()->change();
            $table->bigInteger('realisasi_anggaran_total')->nullable()->change();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
