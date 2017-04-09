<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingTable extends Migration
{

    public function up()
    {
        Schema::create('Setting', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            // Constraints declaration
            $table->string('name');
            $table->string('value')->nullable();

        });
    }

    public function down()
    {
        Schema::drop('Setting');
    }
}
