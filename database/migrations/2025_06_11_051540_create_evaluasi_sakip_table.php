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
        Schema::create('evaluasi_sakip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id');
            $table->foreignId('input_user_id');
            $table->foreignId('last_update_user_id');
            $table->integer('tahun');
            $table->string('periode')->nullable();
            $table->string('penanggung_jawab');
            $table->string('pic_lke');
            $table->string('link_lke');
            $table->float('nilai_komponen_perencanaan_kinerja');
            $table->float('nilai_komponen_pengukuran_kinerja');
            $table->float('nilai_komponen_pelaporan_kinerja');
            $table->float('nilai_komponen_evaluasi_internal');
            $table->float('nilai_total_evaluasi_akip');
            $table->float('nilai_komponen_perencanaan_kinerja_tahun_lalu');
            $table->float('nilai_komponen_pengukuran_kinerja_tahun_lalu');
            $table->float('nilai_komponen_pelaporan_kinerja_tahun_lalu');
            $table->float('nilai_komponen_evaluasi_internal_tahun_lalu');
            $table->float('nilai_total_evaluasi_akip_tahun_lalu');
            $table->text('catatan_komponen_perencanaan_kinerja')->nullable();
            $table->text('catatan_komponen_pengukuran_kinerja')->nullable();
            $table->text('catatan_komponen_pelaporan_kinerja')->nullable();
            $table->text('catatan_komponen_evaluasi_internal')->nullable();
            $table->text('rekomendasi_komponen_perencanaan_kinerja')->nullable();
            $table->text('rekomendasi_komponen_pengukuran_kinerja')->nullable();
            $table->text('rekomendasi_komponen_pelaporan_kinerja')->nullable();
            $table->text('rekomendasi_komponen_evaluasi_internal')->nullable();
            $table->float('angka_kemiskinan')->nullable();
            $table->float('laju_pertumbuhan_ekonomi')->nullable();
            $table->float('tingkat_pengangguran_terbuka')->nullable();
            $table->float('penurunan_emisi_grk')->nullable();
            $table->float('indeks_pembangunan_manusia')->nullable();
            $table->float('indeks_gini_ratio')->nullable();
            $table->float('pendapatan_perkapita')->nullable();
            $table->float('angka_kemiskinan_tahun_lalu')->nullable();
            $table->float('laju_pertumbuhan_ekonomi_tahun_lalu')->nullable();
            $table->float('tingkat_pengangguran_terbuka_tahun_lalu')->nullable();
            $table->float('penurunan_emisi_grk_tahun_lalu')->nullable();
            $table->float('indeks_pembangunan_manusia_tahun_lalu')->nullable();
            $table->float('indeks_gini_ratio_tahun_lalu')->nullable();
            $table->float('pendapatan_perkapita_tahun_lalu')->nullable();
            $table->string('file_evaluasi')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_sakip');
    }
};
