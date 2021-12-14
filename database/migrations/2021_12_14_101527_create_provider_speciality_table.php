<?php

use App\Models\Speciality;
use App\Models\Provider;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderSpecialityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_speciality', function (Blueprint $table) {
            $table->foreignIdFor(Provider::class)->constrained('providers')->cascadeOnDelete();
            $table->foreignIdFor(Speciality::class)->constrained('specialities')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provider_speciality');
    }
}
