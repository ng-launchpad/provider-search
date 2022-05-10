<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAetnaTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('aetna_hospitals')) {
            Schema::create('aetna_hospitals', function (Blueprint $table) {
                $table->id();
                $table->integer('version');
                $table->string('label', 150);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('aetna_languages')) {
            Schema::create('aetna_languages', function (Blueprint $table) {
                $table->id();
                $table->integer('version');
                $table->string('label', 150);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('aetna_specialities')) {
            Schema::create('aetna_specialities', function (Blueprint $table) {
                $table->id();
                $table->integer('version');
                $table->string('label', 150);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aetna_hospitals');
        Schema::dropIfExists('aetna_languages');
        Schema::dropIfExists('aetna_specialities');
    }
}
