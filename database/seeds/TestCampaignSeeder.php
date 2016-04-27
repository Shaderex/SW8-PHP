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
                'snapshot_length' => 1,
                'sample_duration' => 1,
                'sample_frequency' => 1,
                'measurement_frequency' => 1,
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
