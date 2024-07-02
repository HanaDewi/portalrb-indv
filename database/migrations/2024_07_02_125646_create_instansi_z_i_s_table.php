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
            $table->foreignId('instansi_id');
            $table->decimal('skor_bpk');
            $table->decimal('skor_indeks_rb');
            $table->decimal('skor_sakip');
            $table->decimal('skor_maturitas_spip');
            $table->string('opini_bpk');
            $table->string('indeks_rb');
            $table->string('predikat_sakip');
            $table->string('maturitas_spip');
            $table->string('syarat_bpk');
            $table->string('syarat_sakip_wbk');
            $table->string('syarat_sakip_wbbm');
            $table->string('syarat_indeksrb_wbk');
            $table->string('syarat_indeksrb_wbbm');
            $table->string('syarat_maturitas_spip');
            $table->string('syarat_akhir_wbk');
            $table->string('syarat_akhir_wbbm');
            $table->text('keterangan');
            $table->integer('status_akhir');
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
