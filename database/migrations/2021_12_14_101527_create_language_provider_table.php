<?php

use App\Models\Language;
use App\Models\Provider;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguageProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_provider', function (Blueprint $table) {
            $table->foreignIdFor(Language::class)->constrained('languages')->cascadeOnDelete();
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
        Schema::dropIfExists('language_provider');
    }
}
