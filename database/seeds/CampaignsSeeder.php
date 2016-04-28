<?php

use DataCollection\Sensor;
use Illuminate\Database\Seeder;

class CampaignsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campaigns = factory(\DataCollection\Campaign::class, 30)->create();

        $sensors = Sensor::pluck('id');

        $faker = Faker\Factory::create();


        foreach ($campaigns as $campaign) {
            $numberOfSensors = $faker->numberBetween(0, $sensors->count());
            $campaign->sensors()->attach($faker->randomElements($sensors->toArray(), $numberOfSensors));
        }
    }
}
