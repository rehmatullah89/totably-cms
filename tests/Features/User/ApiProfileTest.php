<?php

namespace Tests;

use App\Models\Idea\User;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiProfileTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test case for Profile List
     *
     * @return void
     */
    public function testListProfile()
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
        $this->actingAs($user)->get('admin/profile/', $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Specific Profile Details
     *
     * @return void
     */
    public function testProfileDetail()
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

        $profile_id = 1;

        // calling an api
        $this->actingAs($user)->get('admin/profile/'.$profile_id, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Update Profile
     *
     * @return void
     */
    public function testUpdateProfile()
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
            'user_id'       => '1',
            'first_name'    => 'admin',
            'last_name'     => 'ideatolife',
            'country_id'    => '5',
            'email'         => 'admin@ideatolife.me'
        ];

        // calling an api
        $this->actingAs($user)->post('admin/profile-update/', $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Deleting Profile
     *
     * @return void
     */
    public function testDeleteProfile()
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

        $profile_id = 1;

        // calling an api
        $this->actingAs($user)->delete('admin/profile/'.$profile_id, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Profile of respected user
     *
     * @return void
     */
    public function testProfileByUser()
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
        $this->actingAs($user)->post('/admin/profile-by-user-id', $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }
}
