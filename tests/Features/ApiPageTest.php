<?php
namespace Tests;

use App\Models\Idea\User;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiPageTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test case for Page List
     *
     * @return void
     */
    public function testListPage()
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
        $this->actingAs($user)->get('/admin/pages/', $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Specific Page Details
     *
     * @return void
     */
    public function testPageDetail()
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
        $this->actingAs($user)->get('/admin/pages/'.$id, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Children by Page
     *
     * @return void
     */
    public function testListChildren()
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

        $parent_page_id = 1;

        // calling an api
        $this->actingAs($user)->get('/admin/pages/get-parent-child-pages/'.$parent_page_id, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Create Page
     *
     * @return void
     */
    public function testCreatePage()
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
            'code'  => 'sfdds2000000',
            'url'   => 'testserts.html',
            'translations'  => [
                'kr'        => [
                    'title' => 'ddgvbvsdfg',
                    'body'  => 'body kr'
                ],
                'ar'        => [
                    'title' => 'asdfasdfd',
                    'body'  => 'body arffff'
                ],
                'en'        => [
                    'title' => 'fdfdddd',
                    'body'  => 'body'
                ],
            ]
        ];

        // calling an api
        $this->actingAs($user)->post('/admin/pages', $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Update Page
     *
     * @return void
     */
    public function testUpdatePage()
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

        $page_id    = 1;
        $params     = [
            'code'  => 'sfdds2000000',
            'url'   => 'testserts.html',
            'translations'  => [
                'kr'        => [
                    'title' => 'ddgvbvsdfg',
                    'body'  => 'body kr'
                ],
                'ar'        => [
                    'title' => 'asdfasdfd',
                    'body'  => 'body arffff'
                ],
                'en'        => [
                    'title' => 'fdfdddd',
                    'body'  => 'body'
                ],
            ]
        ];

        // calling an api
        $this->actingAs($user)->post('/admin/pages/'.$page_id, $params, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }

    /**
     * A test case for Deleting Page
     *
     * @return void
     */
    public function testDeletePage()
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

        $page_id = 1;

        // calling an api
        $this->actingAs($user)->delete('/admin/pages/'.$page_id, $headers);
        $this->assertResponseStatus(200);
        $this->seeJson(["status" => "success"]);
    }
}
