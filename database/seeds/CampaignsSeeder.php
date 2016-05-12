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

            $numberOfQuestions = $faker->numberBetween(0, 6);

            $questions = factory(DataCollection\Question::class, 3)->make()->all();
            $campaign->questions()->saveMany($questions);

        }

        $user = factory(\DataCollection\User::class)->create([
            'name' => 'Reality Research Project',
            'email' => 'deploy@aau.dk',
            'password' => bcrypt('dyld1337')
        ]);

        factory(\DataCollection\Campaign::class, 10)->create([
            'user_id' => $user->id
        ]);
    }
}
