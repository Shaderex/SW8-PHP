<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(DataCollection\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(DataCollection\Campaign::class, function (Faker\Generator $faker) {

    $sample_delay = $faker->numberBetween(1, 10000);
    $measurements_per_sample = $faker->numberBetween(1, 10000);
    $measurement_frequency = $faker->numberBetween(0, 15000);
    $sample_duration = $measurement_frequency * $measurements_per_sample;
    $sample_frequency = $sample_delay + $sample_duration;
    $samples_per_snapshot = $faker->numberBetween(1, 50);
    $snapshot_length = $samples_per_snapshot * $sample_frequency;

    return [
        'name' => $faker->company,
        'description' => $faker->paragraph(),
        'is_private' => false,
        'campaign_length' => $faker->numberBetween(0, 40),
        'snapshot_length' => $snapshot_length,
        'samples_per_snapshot' => $samples_per_snapshot,
        'sample_frequency' => $sample_frequency,
        'sample_duration' => $sample_duration,
        'sample_delay' => $sample_delay,
        'measurements_per_sample' => $measurements_per_sample,
        'measurement_frequency' => $measurement_frequency,
        'questionnaire_placement' => $faker->numberBetween(0, 1),
        'user_id' => factory(DataCollection\User::class, 1)->create()->id
    ];
});



$factory->define(DataCollection\Question::class, function (Faker\Generator $faker) {
    $question = $faker->sentence();

    $question = str_replace_last('.', '?', $question);

    return [
        'question' => $question,
    ];
});

$factory->define(DataCollection\Snapshot::class, function (Faker\Generator $faker) {
    return [
        'participant_id' => \DataCollection\Participant::firstOrCreate(['device_id' => 'fjolle'])->id,
        'sensor_data_json' => '{
            "_id": "573e098f86cd096683a6ffdc",
            "company": "ISODRIVE",
            "email": "aileenchristian@isodrive.com",
            "phone": "+1 (904) 573-2138",
            "address": "555 Strong Place, Norvelt, Federated States Of Micronesia, 9004",
            "about": "Nisi laboris duis officia reprehenderit nisi exercitation dolore Lorem nulla eiusmod laborum. Fugiat duis magna ut quis cillum. Eiusmod fugiat dolore nisi id dolore. Voluptate velit nostrud incididunt sunt ex anim. Ex labore ullamco sit aliquip irure Lorem et eiusmod in elit tempor cillum exercitation. Eiusmod duis duis non ut fugiat non veniam Lorem laborum aliqua incididunt in cillum.\r\n",
            "registered": "2014-05-09T02:20:53 -02:00",
            "latitude": 21.3955,
            "longitude": 120.083029
          }',
        'campaign_id' => 1,
    ];
});