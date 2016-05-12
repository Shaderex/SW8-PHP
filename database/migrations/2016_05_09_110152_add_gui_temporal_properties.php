<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGuiTemporalProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->unsignedBigInteger('measurements_per_sample');
            $table->unsignedBigInteger('sample_delay');
            $table->unsignedBigInteger('samples_per_snapshot');
            $table->unsignedBigInteger('snapshot_length')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('measurements_per_sample');
            $table->dropColumn('sample_delay');
            $table->dropColumn('samples_per_snapshot');
        });
    }
}
