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
        Schema::create('lke_test_tp_line', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id');
            $table->foreignId('lke_bobot_id');
            $table->foreignId('penilai_user_id')->nullable();
            $table->foreignId('update_user_id')->nullable();
            $table->float('score')->nullable();
            $table->float('score_index')->nullable();
            $table->text('catatan')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lke_test_tp_line');
    }
};
