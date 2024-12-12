<?php

use \App\Models\Idea\RestaurantTable;
use Illuminate\Database\Seeder;

class RestaurantTableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('tp_restaurant_tables')->truncate();
        $restaurant_table = array(
            array(
                'code' => 1,
                'restaurant_id' => 1
            ),
        );
        RestaurantTable::insert($restaurant_table);
    }
}
