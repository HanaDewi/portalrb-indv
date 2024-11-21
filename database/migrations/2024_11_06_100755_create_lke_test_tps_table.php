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
        Schema::create('lke_test_tp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lke_kegiatan_id');
            $table->foreignId('instansi_id');
            $table->float('rb_general')->nullable();
            $table->float('rb_tematik')->nullable();
            $table->float('index_rb')->nullable();
            $table->float('bobot_rb_general_penyesuaian')->nullable();
            $table->float('rb_general_penyesuaian')->nullable();
            $table->float('koefisien')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lke_test_tp');
    }
};
