<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Room;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Room::class, function (Faker $faker) {
    return [
        'description' => $faker->text,
        'is_rated' => $faker->boolean,
        'is_anonymous' => $faker->boolean,
        'map' => $faker->numberBetween(),
        'seed' => $faker->numberBetween(),
        'max_players' => $faker->numberBetween(Room::MINIMUM_NUM_PLAYERS, Room::MAXIMUM_NUM_PLAYERS),
    ];
});
