<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penghargaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('klpd_instansi_new')->onDelete('cascade');
            $table->year('tahun');
            $table->string('file_sertifikat');
            $table->timestamps();
            $table->softDeletes();

            // Unique constraint: one certificate per instansi per year
            $table->unique(['instansi_id', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penghargaan');
    }
};
