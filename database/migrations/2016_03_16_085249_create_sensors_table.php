<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
        });

        Schema::create('campaign_sensor', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sensor_id');
            $table->integer('campaign_id');

            $table->foreign('sensor_id')->references('id')->on('sensors');
            $table->foreign('campaign_id')->references('id')->on('campaigns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('campaign_sensor');
        Schema::drop('sensors');
    }
}
