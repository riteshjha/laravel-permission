<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Rkj\Permission\Models\Ability;

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

$factory->define(Ability::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'label' => $faker->name,
        'group' => ''
    ];
});
