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
                'samples_per_snapshot' => 100,
                'sample_delay' => 100,
                'measurements_per_sample' => 100,
                'measurement_frequency' => 500,
            ]);

            foreach (Sensor::all('id') as $id) {
                $campaign->sensors()->attach($id);
            }

            $question1 = new Question();
            $question1->question = 'Er du god?';
            $question2 = new Question();
            $question2->question = 'Er du dårlig?';


            $campaign->questions()->save($question1);
            $campaign->questions()->save($question2);

            $campaign->user()->associate(factory(User::class)->create())->save();
        }
    }
}
