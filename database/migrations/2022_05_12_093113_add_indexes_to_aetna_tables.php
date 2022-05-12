<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToAetnaTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aetna_hospital_provider', function (Blueprint $table) {
            $table->index('hospital_id');
            $table->index('provider_id');
        });

        Schema::table('aetna_language_provider', function (Blueprint $table) {
            $table->index('language_id');
            $table->index('provider_id');
        });

        Schema::table('aetna_location_provider', function (Blueprint $table) {
            $table->index('location_id');
            $table->index('provider_id');
        });

        Schema::table('aetna_provider_speciality', function (Blueprint $table) {
            $table->index('speciality_id');
            $table->index('provider_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aetna_hospital_provider', function (Blueprint $table) {
            $table->dropIndex('aetna_hospital_provider_hospital_id_index');
            $table->dropIndex('aetna_hospital_provider_provider_id_index');
        });

        Schema::table('aetna_language_provider', function (Blueprint $table) {
            $table->dropIndex('aetna_language_provider_language_id_index');
            $table->dropIndex('aetna_language_provider_provider_id_index');
        });

        Schema::table('aetna_location_provider', function (Blueprint $table) {
            $table->dropIndex('aetna_location_provider_location_id_index');
            $table->dropIndex('aetna_location_provider_provider_id_index');
        });

        Schema::table('aetna_provider_speciality', function (Blueprint $table) {
            $table->dropIndex('aetna_provider_speciality_speciality_id_index');
            $table->dropIndex('aetna_provider_speciality_provider_id_index');
        });
    }
}
