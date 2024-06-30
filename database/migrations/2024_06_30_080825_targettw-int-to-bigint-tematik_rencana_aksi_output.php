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
        Schema::table('tematik_rencana_aksi_output', function (Blueprint $table) {
            $table->bigInteger('target_tw1')->change();
            $table->bigInteger('target_tw2')->change();
            $table->bigInteger('target_tw3')->change();
            $table->bigInteger('target_tw4')->change();
            $table->bigInteger('target_total')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
