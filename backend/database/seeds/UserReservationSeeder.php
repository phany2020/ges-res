<?php

use Illuminate\Database\Seeder;

class UserReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory(App\UserReservation::class, 20)->make()->each(function ($user_reservation) use ($faker) {
            $users = App\User::all();
            $reservations = App\Reservation::all();
            $user_reservation->user_id = $faker->randomElement($users)->id;
            $user_reservation->reservation_id = $faker->randomElement($reservations)->id;
            $user_reservation->save();
        });
    }
}
