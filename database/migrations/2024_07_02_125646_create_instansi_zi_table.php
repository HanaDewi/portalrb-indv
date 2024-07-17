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
        Schema::create('instansi_zi', function (Blueprint $table) {
            $table->id();
            $table->year('tahun')->nullable();
            $table->foreignId('instansi_id');
            $table->boolean('instansi_wbk_mandiri')->nullable();
            $table->decimal('skor_bpk')->nullable();
            $table->decimal('skor_indeks_rb')->nullable();
            $table->decimal('skor_sakip')->nullable();
            $table->decimal('skor_maturitas_spip')->nullable();
            $table->string('opini_bpk')->nullable();
            $table->string('indeks_rb')->nullable();
            $table->string('predikat_sakip')->nullable();
            $table->string('maturitas_spip')->nullable();
            $table->string('syarat_bpk')->nullable();
            $table->string('syarat_sakip_wbk')->nullable();
            $table->string('syarat_sakip_wbbm')->nullable();
            $table->string('syarat_indeksrb_wbk')->nullable();
            $table->string('syarat_indeksrb_wbbm')->nullable();
            $table->string('syarat_maturitas_spip')->nullable();
            $table->string('syarat_akhir_wbk')->nullable();
            $table->string('syarat_akhir_wbbm')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('status_akhir')->nullable();
            $table->string('pic')->nullable();
            $table->string('email')->nullable();
            $table->string('nomor_kontak')->nullable();
            $table->text('surat_usulan')->nullable();
            $table->text('sptjm')->nullable();
            $table->text('tlhp')->nullable();
            $table->text('survei_mandiri')->nullable();
            $table->integer('jml_wbk')->nullable();
            $table->integer('jml_wbbm')->nullable();
            $table->boolean('final')->nullable();
            $table->integer('update_by')->nullable();
            $table->integer('update_predikat_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instansi_z_i_s');
    }
};
