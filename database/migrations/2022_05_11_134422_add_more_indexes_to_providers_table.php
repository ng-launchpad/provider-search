<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreIndexesToProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->index('npi');
            $table->index('type');
            $table->index('is_accepting_new_patients');
            $table->index('degree');
            $table->index('website');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropIndex('providers_npi_index');
            $table->dropIndex('providers_type_index');
            $table->dropIndex('providers_is_accepting_new_patients_index');
            $table->dropIndex('providers_degree_index');
            $table->dropIndex('providers_website_index');
        });
    }
}
