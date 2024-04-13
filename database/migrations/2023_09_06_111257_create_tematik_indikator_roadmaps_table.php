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
        Schema::create('tematik_indikator_roadmap', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tematik_sasaran_roadmap_id');
            $table->text('nama');
            $table->text('satuan');
            $table->text('target');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tematik_indikator_roadmaps');
    }
};
