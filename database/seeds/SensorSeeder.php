<?php

use DataCollection\Sensor;
use Illuminate\Database\Seeder;

class SensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sensors = [
            ['name' => 'Accelerometer', 'type' => 0],
            ['name' => 'Ambient Light', 'type' => 1],
            ['name' => 'Barometer', 'type' => 2],
            ['name' => 'Cellular', 'type' => 3],
            ['name' => 'Compass', 'type' => 4],
            ['name' => 'Gyroscope', 'type' => 5],
            ['name' => 'Location', 'type' => 6],
            ['name' => 'Proximity', 'type' => 7],
            ['name' => 'Wifi', 'type' => 8],
            ['name' => 'Wrist Accelerometer (Microsoft Band 2)', 'type' => 9],
            ['name' => 'Galvanic Skin Response (Microsoft Band 2)', 'type' => 10],
            ['name' => 'UV (Microsoft Band 2)', 'type' => 11],
            ['name' => 'Heartbeat (Microsoft Band 2)', 'type' => 12],
        ];

        foreach ($sensors as $sensor ) {
            Sensor::firstOrCreate($sensor);
        }

    }
}
