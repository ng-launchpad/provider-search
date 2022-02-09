<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('networks', function (Blueprint $table) {
            $table->id();
            $table->string('label', 150);
            $table->string('search_label', 150);
            $table->string('search_sublabel', 150);
            $table->string('network_label', 150);
            $table->string('browse_label', 150);
            $table->text('legal_home')->nullable();
            $table->text('legal_search')->nullable();
            $table->text('legal_browse')->nullable();
            $table->text('legal_provider')->nullable();
            $table->text('legal_facility')->nullable();
            $table->string('config_key', 150);
            $table->timestamps();

            $table->unique('label');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('networks');
    }
}
