<?php

use App\Models\Hospital;
use App\Models\Provider;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_provider', function (Blueprint $table) {
            $table->foreignIdFor(Hospital::class)->constrained('hospitals')->cascadeOnDelete();
            $table->foreignIdFor(Provider::class)->constrained('providers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hospital_provider');
    }
}
