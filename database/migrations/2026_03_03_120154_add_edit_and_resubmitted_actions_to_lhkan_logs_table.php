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
        Schema::table('lhkan_logs', function (Blueprint $table) {
            $table->enum('action', [
                'created',
                'submitted',
                'approved',
                'rejected',
                'updated',
                'edit_requested',
                'edit_approved',
                'edit_rejected',
                'resubmitted',
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lhkan_logs', function (Blueprint $table) {
            $table->enum('action', ['created', 'submitted', 'approved', 'rejected', 'updated'])->change();
        });
    }
};
