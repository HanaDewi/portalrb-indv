<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministrativesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('regencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id');
            $table->string('name');
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regency_id');
            $table->string('name');
        });

        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id');
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('villages');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('regencies');
        Schema::dropIfExists('provinces');
    }
}