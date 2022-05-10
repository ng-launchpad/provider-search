<?php

use App\Models\State;
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

        if (!Schema::hasTable('aetna_locations')) {
            Schema::create('aetna_locations', function (Blueprint $table) {
                $table->id();
                $table->integer('version');
                $table->string('label', 150)->nullable();
                $table->string('type', 150)->nullable();
                $table->string('address_line_1', 150);
                $table->string('address_line_2', 150)->nullable();
                $table->string('address_city', 150);
                $table->string('address_county', 150)->nullable();
                $table->foreignIdFor(State::class, 'address_state_id')->constrained('states')->restrictOnDelete();
                $table->string('address_zip', 150);
                $table->string('phone', 150)->nullable();
                $table->string('hash', 32)->nullable();
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
        //
    }
}
