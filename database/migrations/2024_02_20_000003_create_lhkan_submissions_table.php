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
        Schema::create('lhkan_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('klpd_instansi_new')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('lhkan_periodes')->onDelete('cascade');
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->integer('jml_aparatur')->default(0);
            $table->integer('jml_wajib_lhkpn')->default(0);
            $table->integer('jml_non_wajib_lhkpn')->default(0);
            $table->integer('realisasi_lhkpn')->default(0);
            $table->integer('realisasi_spt_non_lhkpn')->default(0);
            $table->integer('belum_spt_non_lhkpn')->default(0);
            $table->integer('total_belum_lhkan')->default(0);
            $table->string('link_rekap_gdrive')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lhkan_submissions', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->dropForeign(['periode_id']);
        });
        Schema::dropIfExists('lhkan_submissions');
    }
};