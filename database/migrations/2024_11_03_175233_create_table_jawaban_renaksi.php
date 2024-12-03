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
        Schema::create('jawaban_renaksi', function (Blueprint $table) {
            $table->id();
            $table->integer('instansi_id')->nullable();
            $table->integer('lke_renaksi_id')->nullable();
            $table->string('jawaban', 10)->nullable();
            $table->string('catatan', 100)->nullable();
            $table->string('rekomendasi', 100)->nullable();
            $table->integer('tahun')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_renaksi');
    }
};
