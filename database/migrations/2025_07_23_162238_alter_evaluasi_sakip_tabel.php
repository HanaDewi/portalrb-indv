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
        Schema::table('evaluasi_sakip', function (Blueprint $table) {
            $table->decimal('pendapatan_perkapita_tahun_lalu', 12, 2)->nullable()->change();
            $table->decimal('pendapatan_perkapita', 12, 2)->nullable()->change();
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
