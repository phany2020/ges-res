<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Room;
use Faker\Generator as Faker;

$factory->define(Room::class, function (Faker $faker) {
    return [
        'room_number' => $room_number = $faker->randomNumber,
        'room_state' => $room_state = $faker->randomElement(['SIMPLE','VIP']),
        'status' => $status = $faker->randomElement(['FREE','TAKEN']),        
        'amount' => $amount = $faker->randomNumber,
    ];
});
