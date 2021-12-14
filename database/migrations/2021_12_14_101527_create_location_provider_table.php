<?php

use App\Models\Location;
use App\Models\Provider;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_provider', function (Blueprint $table) {
            $table->foreignIdFor(Location::class)->constrained('locations')->cascadeOnDelete();
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
        Schema::dropIfExists('location_provider');
    }
}
