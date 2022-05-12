<?php

use App\Models\Hospital;
use App\Models\Language;
use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Speciality;
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

        if (!Schema::hasTable('aetna_providers')) {
            Schema::create('aetna_providers', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->integer('version');
                $table->string('npi', 150);
                $table->foreignIdFor(Network::class)->constrained('networks')->restrictOnDelete();
                $table->string('label', 150);
                $table->string('type', 150)->nullable();
                $table->string('degree', 10)->nullable();
                $table->string('website', 150)->nullable();
                $table->enum('gender', [Provider::GENDER_MALE, Provider::GENDER_FEMALE])->nullable();
                $table->boolean('is_facility');
                $table->boolean('is_accepting_new_patients')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('aetna_language_provider')) {
            Schema::create('aetna_language_provider', function (Blueprint $table) {
                $table->string('language_id');
                $table->string('provider_id');
            });
        }

        if (!Schema::hasTable('aetna_hospital_provider')) {
            Schema::create('aetna_hospital_provider', function (Blueprint $table) {
                $table->string('hospital_id');
                $table->string('provider_id');
            });
        }

        if (!Schema::hasTable('aetna_location_provider')) {
            Schema::create('aetna_location_provider', function (Blueprint $table) {
                $table->string('location_id');
                $table->string('provider_id');
                $table->boolean('is_primary')->default(false);
            });
        }

        if (!Schema::hasTable('aetna_provider_speciality')) {
            Schema::create('aetna_provider_speciality', function (Blueprint $table) {
                $table->string('speciality_id');
                $table->string('provider_id');
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
