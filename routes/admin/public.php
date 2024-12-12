<?php
//Auth
$this->app->router->group(
    ['namespace' => 'Auth', 'prefix' => 'admin/auth'],
    function (){
        if (env("ALLOW_FRAMEWORK_ADMIN_LOGIN", 1)) {
            $this->app->router->post('login', 'AuthController@login');
        }
    }
);
