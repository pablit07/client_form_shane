<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRollTable extends Migration
{

    public function up()
    {
        Schema::create('Roll', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            // Constraints declaration
            $table->string('timestamp');
            $table->string('result');
        });
    }

    public function down()
    {
        Schema::drop('Roll');
    }
}
