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
        Schema::create('tematik_permasalahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tematik_indikator_roadmap_id');
            $table->text('nama');
            $table->text('sasaran_permasalahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tematik_permasalahans');
    }
};
