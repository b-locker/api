<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Locker;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Locker::class, function (Faker $faker) {
    return [
        'guid' => Str::random(8),
    ];
});
