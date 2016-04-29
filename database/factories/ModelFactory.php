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
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph(),
        'is_private' => false,
        'campaign_length' => $faker->numberBetween(0, 40),
        'snapshot_length' => $faker->numberBetween(60000),
        'sample_frequency' => $faker->numberBetween(30000, 60000),
        'sample_duration' => $faker->numberBetween(15000, 30000),
        'measurement_frequency' => $faker->numberBetween(0, 15000),
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