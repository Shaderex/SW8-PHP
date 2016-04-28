<?php

use DataCollection\Campaign;
use DataCollection\Question;
use DataCollection\Sensor;
use DataCollection\User;
use Illuminate\Database\Seeder;

class TestCampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!(Campaign::whereName('60SECOND_TEST_CAMP')->exists())) {
            $campaign = Campaign::create([
                'name' => '60SECOND_TEST_CAMP',
                'description' => '60SECOND_TEST_CAMP',
                'is_private' => false,
                'campaign_length' => 3,
                'snapshot_length' => 60000,
                'sample_duration' => 1000,
                'sample_frequency' => 1000,
                'measurement_frequency' => 500,
            ]);

            foreach (Sensor::all('id') as $id) {
                $campaign->sensors()->attach($id);
            }

            $campaign->questions()->save(new Question('Er du god?'));
            $campaign->questions()->save(new Question('Er du dårlig?'));

            $campaign->user()->associate(factory(User::class)->create())->save();
        }
    }
}
