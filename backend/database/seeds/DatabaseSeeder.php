<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Schema::disableForeignKeyConstraints();

        $this->call(CityAndCountrySeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(LaratrustSeeder::class);
        $this->call(HotelSeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(ReservationSeeder::class);
        $this->call(UserReservationSeeder::class);

        Schema::enableForeignKeyConstraints();
        Model::reguard();
    }
}
