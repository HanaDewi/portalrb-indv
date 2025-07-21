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
        Schema::table('general_perencanaan', function (Blueprint $table) {
            $table->dropColumn('baseline_tahun');
            $table->dropColumn('baseline_target');
            $table->dropColumn('baseline_realisasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_perencanaan', function (Blueprint $table) {
            $table->after('indikator_id', function (Blueprint $table) {
                $table->integer('baseline_tahun');
                $table->string('baseline_target');
                $table->string('baseline_realisasi');
            });
        });
    }
};
