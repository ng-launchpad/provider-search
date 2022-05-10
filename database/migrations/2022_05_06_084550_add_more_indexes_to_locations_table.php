<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreIndexesToLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->index('address_line_1');
            $table->index('address_county');
            $table->index('type');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropIndex('locations_address_line_1_index');
            $table->dropIndex('locations_address_county_index');
            $table->dropIndex('locations_type_index');
            $table->dropIndex('locations_phone_index');
        });
    }
}
