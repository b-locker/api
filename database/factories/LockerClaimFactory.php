<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Locker;
use App\Models\LockerClaim;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(LockerClaim::class, function (Faker $faker) {
    $startAt = $faker->dateTimeBetween('-14 days', '+14 days');

    return [
        'client_id' => factory(Client::class)->create()->id,
        'locker_id' => factory(Locker::class)->create()->id,
        'setup_token' => Str::random(),
        'key_hash' => bcrypt('123123'),
        'failed_attempts' => $faker->numberBetween(0, 5),
        'start_at' => $startAt,
        'end_at' => Carbon::instance($startAt)->addDays(7),
    ];
});
