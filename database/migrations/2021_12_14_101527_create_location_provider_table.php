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
            $table->boolean('is_primary')->default(false);
            $table->string('phone', 150)->nullable();
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
