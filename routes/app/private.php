<?php

//Auth
$this->app->router->group(
    ['namespace' => 'Auth', 'prefix' => 'auth'],
    function () {
        $this->app->router->post('verify-email', 'VerifyEmailController@verify');
        $this->app->router->post('logout-user', 'AuthController@logoutUser');
    }
);

//User
$this->app->router->group(
    ['namespace' => 'User', 'prefix' => 'user'],
    function () {
        $this->app->router->post('search', 'UserController@searchUsers');
        $this->app->router->post('update_image', 'ProfileController@updateImage');
        $this->app->router->post('delete_image', 'ProfileController@deleteImage');
        $this->app->router->post('reset_facebook_image', 'ProfileController@resetFacebookImage');
        $this->app->router->post('profile_by_id', 'ProfileController@get');
        $this->app->router->post('notifications', 'NotificationController@get');
        $this->app->router->post('unread_notifications_count', 'NotificationController@unreadNotificationsCount');
        $this->app->router->post('send-notification', 'NotificationController@sendNotification');
        $this->app->router->post('add-feedback', 'FeedbackController@add');
        $this->app->router->post('delete-feedback', 'FeedbackController@delete');
        $this->app->router->post('edit-profile', 'ProfileController@updateProfile');
        $this->app->router->post('change-password', 'ProfileController@changePassword');
    }

);
