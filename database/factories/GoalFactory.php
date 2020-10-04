<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Goal;
use Faker\Generator as Faker;

$factory->define(Goal::class, function (Faker $faker) {
    return [
        'identifier' => $faker->slug,
        'description' => $faker->text,
    ];
});
