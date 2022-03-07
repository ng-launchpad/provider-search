<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->runningUnitTests()) {
            return;
        }

        Schema::table('hospitals', function (Blueprint $table) {
            $table->index('label');
            $table->index('version');
        });

        Schema::table('languages', function (Blueprint $table) {
            $table->fulltext('label');
            $table->index('version');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->index('address_city');
            $table->fulltext('address_zip');
            $table->index('hash');
            $table->index('version');
        });

        Schema::table('networks', function (Blueprint $table) {
            $table->index('label');
        });

        Schema::table('providers', function (Blueprint $table) {
            $table->fulltext('label');
            $table->fulltext('website');
            $table->index('is_facility');
            $table->index('version');
        });

        Schema::table('specialities', function (Blueprint $table) {
            $table->fulltext('label');
            $table->index('version');
        });

        Schema::table('states', function (Blueprint $table) {
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->dropIndex('hospitals_label_index');
            $table->dropIndex('hospitals_version_index');
        });

        Schema::table('languages', function (Blueprint $table) {
            $table->dropIndex('languages_label_fulltext');
            $table->dropIndex('languages_version_index');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->dropIndex('locations_address_city_index');
            $table->dropIndex('locations_address_zip_fulltext');
            $table->dropIndex('locations_hash_index');
            $table->dropIndex('locations_version_index');
        });

        Schema::table('networks', function (Blueprint $table) {
            $table->dropIndex('networks_label_index');
        });

        Schema::table('providers', function (Blueprint $table) {
            $table->dropIndex('providers_is_facility_index');
            $table->dropIndex('providers_label_fulltext');
            $table->dropIndex('providers_website_fulltext');
            $table->dropIndex('providers_version_index');
        });

        Schema::table('specialities', function (Blueprint $table) {
            $table->dropIndex('specialities_label_fulltext');
            $table->dropIndex('specialities_version_index');
        });

        Schema::table('states', function (Blueprint $table) {
            $table->dropIndex('states_code_index');
        });
    }
}
