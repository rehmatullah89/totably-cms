<?php

use App\Jobs\SendPush;
use App\Models\Idea\Device;

if (! function_exists('public_path')) {

    function public_path($path = '')
    {
        return app()->basePath().'/public'.($path ? '/'.$path : $path);
    }
}

if (! function_exists('mail_url')) {

    function mail_url($path = '')
    {
        $url = env("APP_URL", "http://localhost:8000");

        return $url.'/'.$path;
    }
}

if (! function_exists('request')) {
    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('request');
        }

        if (is_array($key)) {
            return app('request')->only($key);
        }

        return app('request')->input($key, $default);
    }
}

if (! function_exists('resource')) {
    function resource($uri, $controller, $withImage = false)
    {
        //$verbs = array('GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE');
        global $app;
        $app->router->get($uri, $controller.'@index');
        $app->router->get($uri.'/{id}', $controller.'@one');
        $app->router->post($uri, $controller.'@store');
        $app->router->post($uri.'/{id}', $controller.'@update');
        $app->router->delete($uri.'/{id}', $controller.'@destroy');
        if ($withImage) {
            $app->router->delete($uri.'/{id}/removeImage', $controller.'@removeImage');
        }
    }
}

if (! function_exists('sendPush')) {
    function sendPush($body, $to = false, $parameters = [], $extraData = [], $title = "", $delay = 0)
    {
        if ($to) {
            //get all user's devices
            $devices = Device::where("user_id", $to)->get();
            if (empty($devices)) {
                \Log::error('Failed Push Notification To user: [' . $to . ']');

                return false;
            }
        } else {
            //get current user
            $devices = [app("DeviceInfo")];
        }

        //collect all devices and add them to the token array
        $data = ['body' => $body];
        foreach ($devices as $device) {
            //if no device type or locale , then ignore the device and continue
            if (empty($device->locale) || empty($device->token)) {
                continue;
            }
            $data['tokens'][$device->locale][] = $device->token;
        }

        //if not devices then return true
        if (empty($data['tokens'])) {
            return true;
        }

        \Log::debug('Checking $extraData data:', [$extraData]);
        $data['parameters'] = $parameters;
        $data['extraData'] = $extraData;
        $data['title'] = $title;

        $job = (new SendPush($data))->delay($delay);

        dispatch($job);
    }
}

if (!function_exists('generate_random_string')) {
    /**
     * Description: The following is used to generate a string of respective length
     * @author Shuja Ahmed - I2L
     * @param $length
     * @return string
     */
    function generate_random_string($length)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $clen = strlen($chars) - 1;
        $id = '';

        for ($i = 0; $i < $length; $i++) {
            $id .= $chars[mt_rand(0, $clen)];
        }
        return ($id);
    }
}

if (!function_exists('doCurlRequest')) {
    /**
     * Description: The following is used to perform Curl Request
     * @param string $request_type
     * @param string $endpoint
     * @param array $headers
     * @param array $postdata
     * @return string
     * @author Hassan Mehmood - I2L
     */
    function doCurlRequest($request_type = 'GET', $endpoint = '', $headers = '', $postdata = [])
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30000);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request_type);

        if($request_type == 'POST') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return json_decode($response);
        }
    }
}
