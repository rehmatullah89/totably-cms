<?php

namespace Tests;

use App\Models\Idea\User;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiFeedbackTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test case for Feedback List
     *
     * @return void
     */
    public function testListFeedback()
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

        $params     = [];

        // calling an api
        $this->actingAs($user)->post('/admin/feedback', $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Specific Feedback Details
     *
     * @return void
     */
    public function testFeedbackDetail()
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
            'id' => 1
        ];

        // calling an api
        $this->actingAs($user)->post('/admin/feedback-by-id', $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Feedback list by user
     *
     * @return void
     */
    public function testListFeedbackByUser()
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
            'user_id' => 2
        ];

        // calling an api
        $this->actingAs($user)->post('/admin/feedback-by-user-id', $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }
}
