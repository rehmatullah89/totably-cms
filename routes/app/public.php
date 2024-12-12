<?php

//Device
$this->app->router->group(
    ['namespace' => 'Device', 'prefix' => 'device'],
    function () {
        $this->app->router->post('update_token', 'TokenController@update');
        $this->app->router->post('change_language', 'TokenController@changeLanguage');
    }
);

//Auth
$this->app->router->group(
    ['namespace' => 'Auth', 'prefix' => 'auth'],
    function () {
        if (env("ALLOW_FRAMEWORK_LOGIN", 1)) {
            $this->app->router->post('login', 'AuthController@login');
        }
        if (env("ALLOW_FRAMEWORK_FB_LOGIN", 1)) {
            $this->app->router->post('fb-login', 'AuthController@fbLogin');
        }
        $this->app->router->post('register', 'RegisterController@registerNewUser');
        $this->app->router->post('check-username', 'RegisterController@checkUsername');
        $this->app->router->post('reset-password', 'ResetPasswordController@resetPassword');
        $this->app->router->post('confirm-password', 'ResetPasswordController@confirmPassword');
    }
);
//User

$this->app->router->group(
    ['namespace' => 'User', 'prefix' => 'user'],
    function () {
        $this->app->router->get('static-page', 'PageController@pageByCode');
        $this->app->router->get('static-page-child', 'PageController@staticPageChild');

    }
);
