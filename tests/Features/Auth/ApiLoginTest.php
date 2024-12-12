<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiLoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test case for Login Api
     *
     * @return void
     */
    public function testBasicTest()
    {
        // header variable for the request
        $headers = [
            'x-device-uuid' => env('X-DEVICE-UUID'),
            'type' => env('DEVICE-TYPE'),
            'version' => env('DEVICE-VERSION'),
        ];

        $params = [
            'username' => 'admin@ideatolife.me',
            'password' => 'admin1asd12h'
        ];

        // calling an api
        $this->post('/admin/auth/login', $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }
}
