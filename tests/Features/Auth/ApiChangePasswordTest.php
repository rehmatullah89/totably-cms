<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiChangePasswordTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test case for Change Password Api
     *
     * @return void
     */
    public function testBasicTest()
    {
        // So Tests can run without any interruption
        $this->withoutMiddleware();

        // header variable for the request
        $headers = [
            'x-device-uuid' => env('X-DEVICE-UUID'),
            'type' => env('DEVICE-TYPE'),
            'version' => env('DEVICE-VERSION'),
        ];

        // Change Password first time
        $firstCredentials = [
            'user_id' => '2',
            'current_password' => 'admin1asd12h',
            'new_password' => 'admin1234',
            'confirm_password' => 'admin1234',
        ];

        // roll back changes so it don't conflict with other API's
        $secondCredentials = [
            'user_id' => '2',
            'current_password' => 'admin1234',
            'new_password' => 'admin1asd12h',
            'confirm_password' => 'admin1asd12h',
        ];

        // calling an api
        $this->post('/admin/change-password', $firstCredentials, $headers);

        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }
}
