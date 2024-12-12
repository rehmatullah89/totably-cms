<?php

namespace Tests;

use App\Models\Idea\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiRestaurantTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test case for Restaurant List
     *
     * @return void
     */
    public function testListRestaurant()
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
        $this->actingAs($user)->get('admin/restaurants/', $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Specific Restaurant Details
     *
     * @return void
     */
    public function testRestaurantDetail()
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

        $restaurant_id = 1;

        // calling an api
        $this->actingAs($user)->get('admin/restaurants/'.$restaurant_id, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Create Restaurant
     *
     * @return void
     */
    public function testCreateRestaurant()
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
            'location_id' => 'cjgedBMiaads',
            'name' => 'MATTO 20',
            'description' => 'Totably offers: Pay for the whole bill using totably.',
            'address' => 'The Oberoi, Business Bay - Dubai',
            'phone' => '111000000',
            'email' => 'abc@ideatolife.me',
            'working_hours' => '12:00 am - 12:00 pm',
            'user_id' => 2
        ];

        // calling an api
        $this->actingAs($user)->post('admin/restaurants/', $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Update Restaurant
     *
     * @return void
     */
    public function testUpdateRestaurant()
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

        $restaurant_id    = 1;
        $params     = [
            'name' => 'MATTO 2',
            'description' => 'Totably offers: Pay for the whole bill using totably.',
            'address' => 'The Oberoi, Business Bay - Dubai',
            'phone' => '111000000',
            'email' => 'abc@ideatolife.me',
            'working_hours' => '12:00 am - 12:00 pm',
            'user_id' => 2
        ];

        // calling an api
        $this->actingAs($user)->post('admin/restaurants/'.$restaurant_id, $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Deleting Restaurant
     *
     * @return void
     */
    public function testDeleteRestaurant()
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

        $restaurant_id = 1;

        // calling an api
        $this->actingAs($user)->delete('admin/restaurants/'.$restaurant_id, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Restaurant of respected user
     *
     * @return void
     */
    public function testRestaurantByUser()
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
            'user_id'       => '1'
        ];

        // calling an api
        $this->actingAs($user)->post('/admin/restaurant-by-user-id', $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }
}
