<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignParticipantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_participant', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('campaign_id');
            $table->integer('participant_id');

            $table->foreign('campaign_id')->references('id')->on('campaigns');
            $table->foreign('participant_id')->references('id')->on('participants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('campaign_participant');
    }
}
