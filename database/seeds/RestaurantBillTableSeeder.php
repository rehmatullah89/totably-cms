<?php

use \App\Models\Idea\RestaurantBill;
use Illuminate\Database\Seeder;

class RestaurantBillTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * insert restaurant bill
     */
    public function run()
    {
        \DB::table('tp_payment_logs')->truncate();
        $restaurantbill = array(
            array(
                'ticket_id' => '221',
                'item_id' => '10001',
                'amount_paid' => '120',
                'paid_for' => '1',
                'pay_type' => 'full',
                'table_id' => '1',
                'user_id' => 2
            ),
        );
        RestaurantBill::insert($restaurantbill);
    }
}
