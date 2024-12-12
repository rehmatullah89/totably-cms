<?php

namespace Tests;

use App\Models\Idea\User;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiNotificationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test case for Notification List
     *
     * @return void
     */
    public function testListNotification()
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
        $this->actingAs($user)->get('/admin/notifications/history/', $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Specific Notification Details
     *
     * @return void
     */
    public function testNotificationDetail()
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

        $id = 1;

        // calling an api
        $this->actingAs($user)->get('/admin/notifications/history/'.$id, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Create Notification
     *
     * @return void
     */
    public function testCreateNotification()
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

        $params     = [
            'target_id'  => '2',
            'text'       => 'test send notification 2',
            'push_date'  => '2019-11-14 11:40:00'
        ];

        // calling an api
        $this->actingAs($user)->post('/admin/notifications/history', $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Update Notification
     *
     * @return void
     */
    public function testUpdateNotification()
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

        $notification_id    = 1;
        $params     = [
            'target_id'  => '2',
            'text'       => 'test send notification 2',
            'push_date'  => '2019-11-14 11:40:00'
        ];

        // calling an api
        $this->actingAs($user)->post('/admin/notifications/history/'.$notification_id, $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Deleting Notification
     *
     * @return void
     */
    public function testDeleteNotification()
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

        $notification_id = 1;

        // calling an api
        $this->actingAs($user)->delete('/admin/notifications/history/'.$notification_id, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }
}
