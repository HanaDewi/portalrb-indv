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
        Schema::create('jawaban_coi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id'); 
            $table->foreignId('user_id'); // Supaya tahu username yang isi
            $table->foreignId('master_pertanyaan_id'); 
            $table->text('nilai_jawaban')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_cois');
    }
};
