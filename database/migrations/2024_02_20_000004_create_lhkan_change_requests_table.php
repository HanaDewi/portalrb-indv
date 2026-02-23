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
        Schema::create('lhkan_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('lhkan_submissions')->onDelete('cascade');
            $table->string('field_name');
            $table->text('old_value');
            $table->text('new_value');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lhkan_change_requests');
    }
};