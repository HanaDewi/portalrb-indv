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
            $table->double('target_tw1')->default(0)->change();
            $table->double('target_tw2')->default(0)->change();
            $table->double('target_tw3')->default(0)->change();
            $table->double('target_tw4')->default(0)->change();
            $table->double('target_total')->default(0)->change();
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
