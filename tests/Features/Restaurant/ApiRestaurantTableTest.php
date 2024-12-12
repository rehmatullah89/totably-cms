<?php

namespace Tests;

use App\Models\Idea\User;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiRestaurantTableTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test case for RestaurantTable List
     *
     * @return void
     */
    public function testListRestaurantTable()
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
        $this->actingAs($user)->get('admin/restaurants-table/', $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Specific RestaurantTable Details
     *
     * @return void
     */
    public function testRestaurantTableDetail()
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

        $restaurant_table_id = 1;

        // calling an api
        $this->actingAs($user)->get('admin/restaurants-table/'.$restaurant_table_id, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Create RestaurantTable
     *
     * @return void
     */
    public function testCreateRestaurantTable()
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
            'restaurant_id' => 1,
            'code'          => [
                'Table 2',
                'Table 3',
            ]
        ];

        // calling an api
        $this->actingAs($user)->post('admin/restaurants-table/', $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Update RestaurantTable
     *
     * @return void
     */
    public function testUpdateRestaurantTable()
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

        $restaurant_table_id = 1;
        $params           = [
            'code'       => 'Table 200'
        ];

        // calling an api
        $this->actingAs($user)->post('admin/restaurants-table/'.$restaurant_table_id, $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Deleting RestaurantTable
     *
     * @return void
     */
    public function testDeleteRestaurantTable()
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

        $restaurant_table_id = 1;

        // calling an api
        $this->actingAs($user)->delete('admin/restaurants-table/'.$restaurant_table_id, $headers);
        //dd($this->response);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for RestaurantTable of respected restaurant
     *
     * @return void
     */
    public function testRestaurantTableByRestaurant()
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
        $this->actingAs($user)->post('/admin/table-by-restaurant-id', $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }
}
