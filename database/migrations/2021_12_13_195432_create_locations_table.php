<?php

use App\Models\State;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('label', 150);
            $table->string('type', 150);
            $table->string('address_line_1', 150);
            $table->string('address_city', 150);
            $table->string('address_county', 150)->nullable();
            $table->foreignIdFor(State::class, 'address_state_id')->constrained('states')->restrictOnDelete();
            $table->string('address_zip', 150);
            $table->string('phone', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
