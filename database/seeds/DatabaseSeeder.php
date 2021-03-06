<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SensorSeeder::class);
        $this->call(TestCampaignSeeder::class);
        $this->call(CampaignsSeeder::class);
    }
}
