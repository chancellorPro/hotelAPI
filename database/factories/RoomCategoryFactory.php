<?php

/** @var Factory $factory */

use App\Models\RoomCategory;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

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

$roomCategories = [
  'Penthouse',
  'Presidential suite',
  'Executive suite',
  'Standard suite',
  'Studio suite',
];

$factory->define(RoomCategory::class, function (Faker $faker) use(&$roomCategories) {
    return [
        'title' => array_shift($roomCategories),
    ];
});
