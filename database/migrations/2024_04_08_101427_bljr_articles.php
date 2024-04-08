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

        Schema::create('bljr_articles', function (Blueprint $table) {
            $table->id();
            $table->string('language');
            $table->string('author_id');
            $table->string('image');
            $table->string('title');
            $table->string('slug');
            $table->string('content');
            $table->string('meta-title');
            $table->string('meta-description');
            $table->boolean('is_breaking_news');
            $table->boolean('show_at_slider');
            $table->boolean('show_at_populer');
            $table->boolean('status');
            $table->boolean('is_approved');
            $table->integer('views');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
