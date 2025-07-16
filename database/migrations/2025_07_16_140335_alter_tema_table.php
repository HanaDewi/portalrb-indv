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
        Schema::table('tema', function (Blueprint $table) {
            $table->after('id', function(Blueprint $table){
                $table->integer('tahun');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tema', function (Blueprint $table) {
            $table->dropColumn('tahun');
        });
    }
};
