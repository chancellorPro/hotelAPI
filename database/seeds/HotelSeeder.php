<?php

use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoryIds = factory(App\Models\RoomCategory::class, 5)->create()->pluck('id')->toArray();

        return factory(App\Models\Hotel::class, 10)
            ->create()
            ->each(function ($hotel) use ($categoryIds) {
                $hotel->rooms()->saveMany(
                    factory(App\Models\Room::class, 100)->make()->each(function ($room) use ($categoryIds) {
                        $room->category_id = rand(1, count($categoryIds)
                        );
                    }));
            });
    }
}
