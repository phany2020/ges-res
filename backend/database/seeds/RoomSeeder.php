<?php

use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory(App\Room::class,20)->make()->each(function ($room) use ($faker){
            $hotel= App\Hotel::all();
            $room->hotel_id = $faker->randomElement($hotel)->id;
            $room->save();     
        }); 
    }
}
