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
        Schema::create('konversi_jawaban_renaksi', function (Blueprint $table) {
            $table->id();
            $table->string('jawaban', 10)->nullable();
            $table->float('skor')->nullable();
            $table->integer('tahun')->nullable();
            $table->timestamps();
        });
        DB::table('konversi_jawaban_renaksi')->truncate();
        DB::table('konversi_jawaban_renaksi')->insert(array(
            [ 'jawaban'=> 'A', 'skor'=> 1.0, 'tahun'=>2024 ],
            [ 'jawaban'=> 'B', 'skor'=> 0.75, 'tahun'=>2024 ],
            [ 'jawaban'=> 'C', 'skor'=> 0.5, 'tahun'=>2024 ],
            [ 'jawaban'=> 'D', 'skor'=> 0.25, 'tahun'=>2024 ],
            [ 'jawaban'=> 'E', 'skor'=> 0.0, 'tahun'=>2024 ],
            [ 'jawaban'=> 'YA', 'skor'=> 1.0, 'tahun'=>2024 ],
            [ 'jawaban'=> 'TIDAK', 'skor'=> 0.0, 'tahun'=>2024 ]
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konversi_jawaban_renaksi');
    }
};
