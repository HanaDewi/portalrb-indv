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
        Schema::create('lhkan_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('klpd_instansi_new')->onDelete('cascade');
            $table->string('nama');
            $table->string('nomor_hp');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->enum('added_by', ['admin', 'instansi'])->default('admin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lhkan_pics', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
        });
        Schema::dropIfExists('lhkan_pics');
    }
};