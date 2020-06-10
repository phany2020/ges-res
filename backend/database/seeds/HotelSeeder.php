<?php

use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory(App\Hotel::class,20)->make()->each(function ($hotel) use ($faker){
            $hotel->save();
        });
    }
}
