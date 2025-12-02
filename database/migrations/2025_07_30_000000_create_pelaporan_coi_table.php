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
        Schema::create('pelaporan_coi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->unique();
            $table->boolean('q1_peraturan_internal')->nullable();
            $table->string('q11_nomor_peraturan')->nullable();
            $table->boolean('q2_selaras_permepan')->nullable();
            $table->boolean('q21_susun_revisi')->nullable();
            $table->text('q211_rencana_penyesuaian')->nullable();
            $table->boolean('q3_pedoman_teknis')->nullable();
            $table->string('q31_nomor_pedoman')->nullable();
            $table->boolean('q4_penunjukan_pejabat')->nullable();
            $table->boolean('q5_sistem_aplikasi')->nullable();
            $table->boolean('q6_pencatatan_register')->nullable();
            $table->unsignedBigInteger('q61_total_wajib')->nullable();
            $table->unsignedBigInteger('q62_total_lapor')->nullable();
            $table->boolean('q7_deklarasi_aktual')->nullable();
            $table->string('q71_jumlah_deklarasi')->nullable();
            $table->boolean('q8_lini_aduan')->nullable();
            $table->string('q81_nama_lini')->nullable();
            $table->boolean('q9_monev')->nullable();
            $table->boolean('q10_laporan')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelaporan_coi');
    }
};
