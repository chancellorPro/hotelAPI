<?php

use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return factory(App\Models\Room::class)->make();
    }
}
