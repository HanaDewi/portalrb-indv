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
        Schema::create('unit_zi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_zi_id');
            $table->text('nama')->nullable();
            $table->text('lke')->nullable();
            $table->boolean('afirmasi')->nullable();
            $table->boolean('wbk')->nullable();
            $table->boolean('wbbm')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_z_i_s');
    }
};
