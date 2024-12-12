<?php

namespace Tests;

use App\Models\Idea\User;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiPermissionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test case for Permission List
     *
     * @return void
     */
    public function testListPermission()
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
        $this->actingAs($user)->get('/admin/roles-list-permissions', $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }
}
