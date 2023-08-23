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
        Schema::create('general_rencana_aksi_output', function (Blueprint $table) {
            $table->id();
            $table->foreignId('general_rencana_aksi_id');
            $table->string('satuan_output');
            $table->string('indikator_output');
            $table->integer('target_tw1');
            $table->integer('target_tw2');
            $table->integer('target_tw3');
            $table->integer('target_tw4');
            $table->integer('target_total');
            $table->integer('anggaran_tw1');
            $table->integer('anggaran_tw2');
            $table->integer('anggaran_tw3');
            $table->integer('anggaran_tw4');
            $table->integer('anggaran_total');
            $table->string('pelaksana');
            $table->string('koordinator');
            $table->string('realisasi_output')->nullable();
            $table->string('realisasi_anggaran')->nullable();
            $table->string('capaian_anggaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_rencana_aksi_output');
    }
};
