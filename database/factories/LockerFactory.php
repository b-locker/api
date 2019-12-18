<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Locker;
use Faker\Generator as Faker;

$factory->define(Locker::class, function (Faker $faker) {
    return [
        'guid' => substr(md5(rand()), 0, 8),
    ];
});
