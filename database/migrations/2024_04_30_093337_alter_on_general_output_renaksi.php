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
            $table->float('target_tw1')->nullable()->change();
            $table->float('target_tw2')->nullable()->change();
            $table->float('target_tw3')->nullable()->change();
            $table->float('target_tw4')->nullable()->change();
            $table->float('target_total')->nullable()->change();
            $table->bigInteger('anggaran_total')->nullable()->change();
            $table->float('realisasi_output_tw1')->nullable()->change();
            $table->float('realisasi_output_tw2')->nullable()->change();
            $table->float('realisasi_output_tw3')->nullable()->change();
            $table->float('realisasi_output_tw4')->nullable()->change();
            $table->float('realisasi_output_total')->nullable()->change();
            $table->bigInteger('realisasi_anggaran_total')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_rencana_aksi_output', function (Blueprint $table) {
            //
        });
    }
};
