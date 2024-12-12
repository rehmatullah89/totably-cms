<?php

namespace Tests;

use App\Models\Idea\User;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiCountryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test case for Country List
     *
     * @return void
     */
    public function testListCountry()
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
        $this->actingAs($user)->get('/admin/countries/all', $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Specific Country Details
     *
     * @return void
     */
    public function testCountryDetail()
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

        $params = [
            'country_id' => 2
        ];

        // calling an api
        $this->actingAs($user)->post('/admin/countries', $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }
}
