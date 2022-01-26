<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class AddDefaultSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::set('version', 0);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::unset('version');
    }
}
