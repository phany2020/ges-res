<?php

use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory(App\Reservation::class,20)->make()->each(function ($reservation) use ($faker){
            $room= App\Room::all();
            $reservation->room_id = $faker->randomElement($room)->id;
            $reservation->save();     
        }); 
    }
}
