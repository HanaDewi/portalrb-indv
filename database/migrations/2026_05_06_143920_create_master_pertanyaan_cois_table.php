<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('master_pertanyaan_coi', function (Blueprint $table) {
            $table->id();
            $table->string('teks_pertanyaan');
            $table->string('tipe_jawaban'); // pilihan_ganda, jawaban_singkat, dll
            $table->json('opsi')->nullable(); // Untuk simpan pilihan A, B, C
            $table->boolean('is_wajib')->default(false);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_pertanyaan_cois');
    }
};
