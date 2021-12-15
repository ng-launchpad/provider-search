<?php

use App\Models\Network;
use App\Models\Provider;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
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

            $table->unique(['npi', 'network_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('providers');
    }
}
