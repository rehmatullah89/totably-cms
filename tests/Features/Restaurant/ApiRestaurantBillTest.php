<?php

namespace Tests;

use App\Models\Idea\User;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiRestaurantBillTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test case for RestaurantBill List
     *
     * @return void
     */
    public function testListRestaurantBill()
    {
        // So Tests can run without any interruption
        $this->withoutMiddleware();

        // Mocking a user so it can be used as an Auth user
        $user = User::findOrFail(env('TESTING-USER-ID'));

        // header variable for the request
        $headers = [
            'x-device-uuid' => env('X-DEVICE-UUID'),
            'type' => env('DEVICE-TYPE'),
            'version' => env('DEVICE-VERSION'),
        ];

        // calling an api
        $this->actingAs($user)->get('admin/restaurants-bill/', $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Specific RestaurantBill Details
     *
     * @return void
     */
    public function testRestaurantBillDetail()
    {
        // So Tests can run without any interruption
        $this->withoutMiddleware();

        // Mocking a user so it can be used as an Auth user
        $user = User::findOrFail(env('TESTING-USER-ID'));

        // header variable for the request
        $headers = [
            'x-device-uuid' => env('X-DEVICE-UUID'),
            'type' => env('DEVICE-TYPE'),
            'version' => env('DEVICE-VERSION'),
        ];

        $restaurant_bill_id = 1;

        // calling an api
        $this->actingAs($user)->get('admin/restaurants-bill/'.$restaurant_bill_id, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for RestaurantBill of respected restaurant
     *
     * @return void
     */
    public function testRestaurantBillByRestaurant()
    {
        // So Tests can run without any interruption
        $this->withoutMiddleware();

        // Mocking a user so it can be used as an Auth user
        $user       = User::findOrFail(env('TESTING-USER-ID'));

        // header variable for the request
        $headers    = [
            'x-device-uuid' => env('X-DEVICE-UUID'),
            'type' => env('DEVICE-TYPE'),
            'version' => env('DEVICE-VERSION'),
        ];

        $params     = [
            'restaurant_id' => '1'
        ];

        // calling an api
        $this->actingAs($user)->post('/admin/bill-by-restaurant-id', $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for RestaurantBill of respected table
     *
     * @return void
     */
    public function testRestaurantBillByTable()
    {
        // So Tests can run without any interruption
        $this->withoutMiddleware();

        // Mocking a user so it can be used as an Auth user
        $user       = User::findOrFail(env('TESTING-USER-ID'));

        // header variable for the request
        $headers    = [
            'x-device-uuid' => env('X-DEVICE-UUID'),
            'type' => env('DEVICE-TYPE'),
            'version' => env('DEVICE-VERSION'),
        ];

        $params     = [
            'table_id' => '1'
        ];

        // calling an api
        $this->actingAs($user)->post('/admin/bill-by-table-id', $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }
}
