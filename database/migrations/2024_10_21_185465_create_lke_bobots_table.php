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
        Schema::create('lke_bobot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lke_parameter_id');
            $table->string('group');
            $table->float('min_value')->nullable();
            $table->float('max_value')->nullable();
            $table->float('bobot')->nullable();
            $table->float('target_baik')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lke_bobot');
    }
};
