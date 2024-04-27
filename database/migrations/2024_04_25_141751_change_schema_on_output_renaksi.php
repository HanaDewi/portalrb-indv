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
        Schema::table('tematik_rencana_aksi_output', function (Blueprint $table) {
            $table->integer('anggaran_tw1')->nullable()->change();
            $table->integer('anggaran_tw2')->nullable()->change();
            $table->integer('anggaran_tw3')->nullable()->change();
            $table->integer('anggaran_tw4')->nullable()->change();
            $table->integer('realisasi_anggaran_tw1')->nullable()->change();
            $table->integer('realisasi_anggaran_tw2')->nullable()->change();
            $table->integer('realisasi_anggaran_tw3')->nullable()->change();
            $table->integer('realisasi_anggaran_tw4')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tematik_rencana_aksi_output', function (Blueprint $table) {
            //
        });
    }
};
