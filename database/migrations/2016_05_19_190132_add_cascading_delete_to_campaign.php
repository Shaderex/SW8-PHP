<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCascadingDeleteToCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('snapshots', function (Blueprint $table) {
            $table->dropForeign('snapshots_campaign_fk');
            $table->foreign('campaign_id', 'snapshots_campaign_fk')
                ->references('id')
                ->on('campaigns')
                ->onDelete('cascade');
        });

        Schema::table('campaign_sensor', function (Blueprint $table) {
            $table->dropForeign('campaign_sensor_campaign_id_foreign');
            $table->dropForeign('campaign_sensor_sensor_id_foreign');

            $table->foreign('sensor_id')->references('id')->on('sensors')->onDelete('cascade');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign('questions_campaign_id_foreign');

            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('snapshots', function (Blueprint $table) {
            $table->dropForeign('snapshots_campaign_fk');
            $table->foreign('campaign_id', 'snapshots_campaign_fk')
                ->references('id')
                ->on('campaigns');
        });

        Schema::table('campaign_sensor', function (Blueprint $table) {
            $table->dropForeign('campaign_sensor_campaign_id_foreign');
            $table->dropForeign('campaign_sensor_sensor_id_foreign');

            $table->foreign('sensor_id')->references('id')->on('sensors');
            $table->foreign('campaign_id')->references('id')->on('campaigns');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign('questions_campaign_id_foreign');

            $table->foreign('campaign_id')->references('id')->on('campaigns');
        });
    }
}
