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
        Schema::create('general_perencanaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id');
            $table->foreignId('kegiatan_utama_id');
            $table->foreignId('indikator_id');
            $table->integer('baseline_tahun');
            $table->string('baseline_target');
            $table->string('baseline_realisasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_perencanaan');
    }
};
