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
        Schema::create('lke_parameter', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('level');
            $table->smallInteger('tahun');
            $table->integer('parent_id')->nullable();
            $table->integer('penilai_id')->nullable();
            $table->boolean('rencana_aksi')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lke_parameter');
    }
};
