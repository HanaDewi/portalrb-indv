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
        Schema::table('lhkan_submissions', function (Blueprint $table) {
            $table->enum('status', [
                'draft',
                'submitted',
                'approved',
                'rejected',
                'edit_requested',
                'edit_approved',
            ])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lhkan_submissions', function (Blueprint $table) {
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft')->change();
        });
    }
};
