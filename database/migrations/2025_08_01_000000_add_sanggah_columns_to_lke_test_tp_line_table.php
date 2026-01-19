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
        Schema::table('lke_test_tp_line', function (Blueprint $table) {
            $table->string('status_sanggah')->nullable()->after('rekomendasi');
            $table->text('keterangan_sanggah')->nullable()->after('status_sanggah');
            $table->string('file_sanggah')->nullable()->after('keterangan_sanggah');
            $table->string('pengaju_sanggah')->nullable()->after('file_sanggah');
            $table->text('keterangan_tanggapan_sanggah')->nullable()->after('pengaju_sanggah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lke_test_tp_line', function (Blueprint $table) {
            $table->dropColumn([
                'status_sanggah',
                'keterangan_sanggah',
                'file_sanggah',
                'pengaju_sanggah',
                'keterangan_tanggapan_sanggah',
            ]);
        });
    }
};
