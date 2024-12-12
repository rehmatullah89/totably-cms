<?php

use App\Models\Idea\Restaurant;
use Illuminate\Database\Seeder;

class RestaurantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * insert restaurant user
     */
    public function run()
    {
        \DB::table('tp_restaurants')->truncate();
        $restaurant = array(
            array(
                'location_id' => 'TngMM5gc',
                'name' => 'MATTO',
                'description' => 'Totably offers: Pay for the whole bill using totably.',
                'address' => 'The Oberoi, Business Bay - Dubai',
                'phone' => '111000000',
                'email' => 'hassan.mehmood@ideatolife.me',
                'working_hours' => '12:00 am - 12:00 pm',
                'user_id' => 2
            ),
        );
        Restaurant::insert($restaurant);
    }
}
