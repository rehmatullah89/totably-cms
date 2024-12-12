<?php

//User
$this->app->router->group(
    ['namespace' => 'User', 'prefix' => 'admin'],
    function () {
        //User's resource
        $this->app->router->get('users', 'UserController@index');
        $this->app->router->get('users/{id}', 'UserController@one');
        $this->app->router->post('users', 'UserController@store');
        $this->app->router->post('users/{id}', 'UserController@update');
        $this->app->router->delete('users/{id}', 'UserController@destroy');

        //Profile
        $this->app->router->get('profile', 'ProfileController@index');
        $this->app->router->get('profile/{id}', 'ProfileController@one');
        $this->app->router->post('profile-by-user-id', 'ProfileController@profileByUserId');
        $this->app->router->post('profile-update', 'ProfileController@editProfileByUserId');
        $this->app->router->post('change-password', 'ProfileController@changePassword');
        $this->app->router->delete('profile/{id}', 'ProfileController@destroy');
    }
);

//Role
$this->app->router->group(
    ['namespace' => 'Role', 'prefix' => 'admin'],
    function () {
        //Role - Permissions - action
        $this->app->router->get('roles', 'RoleController@getAllRoles');
        $this->app->router->post('roles/permissions-by-role', 'RoleController@permissionsByRole');
        $this->app->router->post('roles/permissions-by-user', 'RoleController@permissionsByUser');
        $this->app->router->post('roles/{id}', 'RoleController@update');
        $this->app->router->post('roles-set-permissions/{id}', 'RoleController@setRolePermissions');
        $this->app->router->post('roles-list-actions', 'ActionController@index');
        $this->app->router->get('roles-list-permissions', 'PermissionController@index');
    }
);

//Other
$this->app->router->group(
    ['prefix' => 'admin'],
    function () {
        //Pages resource
        $this->app->router->get('pages', 'PageController@index');
        $this->app->router->get('pages/{id}', 'PageController@one');
        $this->app->router->get('pages/get-parent-child-pages/{id}', 'PageController@getParentChildPages');
        $this->app->router->post('pages', 'PageController@store');
        $this->app->router->post('pages/{id}', 'PageController@update');
        $this->app->router->delete('pages/{id}', 'PageController@destroy');

        //Configuration
        $this->app->router->get('configurations', 'ConfigurationController@index');
        $this->app->router->post('configurations/update', 'ConfigurationController@updateAll');

        //Feedback
        $this->app->router->post('feedback', 'FeedbackController@index');
        $this->app->router->post('feedback-by-id', 'FeedbackController@one');
        $this->app->router->post('feedback-by-user-id', 'FeedbackController@feedbackByUserId');

        //Countries
        $this->app->router->get('countries/all', 'CountryController@index');
        $this->app->router->post('countries', 'CountryController@one');

        //Notification
        $this->app->router->get('notifications/history', 'PushNotificationHistoryController@index');
        $this->app->router->get('notifications/history/{id}', 'PushNotificationHistoryController@one');
        $this->app->router->post('notifications/history', 'PushNotificationHistoryController@store');
        $this->app->router->post('notifications/history/{id}', 'PushNotificationHistoryController@update');
        $this->app->router->delete('notifications/history/{id}', 'PushNotificationHistoryController@destroy');
    }
);

//Restaurant
$this->app->router->group(
    ['namespace' => 'Restaurant', 'prefix' => 'admin'],
    function () {
        $this->app->router->get('restaurants', 'RestaurantController@index');
        $this->app->router->get('restaurants/{id}', 'RestaurantController@one');
        $this->app->router->post('restaurant-by-user-id', 'RestaurantController@restaurantByUserId');
        $this->app->router->post('restaurants', 'RestaurantController@store');
        $this->app->router->post('restaurants/{id}', 'RestaurantController@update');
        $this->app->router->delete('restaurants/{id}', 'RestaurantController@destroy');

        // Restaurant Table
        $this->app->router->get('restaurants-table', 'RestaurantTableController@index');
        $this->app->router->get('restaurants-table/{id}', 'RestaurantTableController@one');
        $this->app->router->post('table-by-restaurant-id', 'RestaurantTableController@tableByRestaurantId');
        $this->app->router->post('restaurants-table', 'RestaurantTableController@store');
        $this->app->router->post('restaurants-table/{id}', 'RestaurantTableController@update');
        $this->app->router->delete('restaurants-table/{id}', 'RestaurantTableController@destroy');

        // Restaurant Gallery
        $this->app->router->get('restaurants-gallery', 'RestaurantGalleryController@index');
        $this->app->router->get('restaurants-gallery/{id}', 'RestaurantGalleryController@one');
        $this->app->router->post('gallery-by-restaurant-id', 'RestaurantGalleryController@galleryByRestaurantId');
        $this->app->router->post('restaurants-gallery', 'RestaurantGalleryController@store');
        $this->app->router->post('restaurants-gallery/{id}', 'RestaurantGalleryController@update');
        $this->app->router->delete('restaurants-gallery/{id}', 'RestaurantGalleryController@destroy');

        // Restaurant Menu
        $this->app->router->get('restaurants-menu', 'RestaurantMenuController@index');
        $this->app->router->get('restaurants-menu/{id}', 'RestaurantMenuController@one');
        $this->app->router->post('menu-by-restaurant-id', 'RestaurantMenuController@menuByRestaurantId');
        $this->app->router->post('restaurants-menu', 'RestaurantMenuController@store');
        $this->app->router->post('restaurants-menu/{id}', 'RestaurantMenuController@update');
        $this->app->router->delete('restaurants-menu/{id}', 'RestaurantMenuController@destroy');

        // Restaurant Bill
        $this->app->router->get('restaurants-bill', 'RestaurantBillController@index');
        $this->app->router->get('restaurants-bill/{id}', 'RestaurantBillController@one');
        $this->app->router->post('bill-by-restaurant-id', 'RestaurantBillController@billByRestaurantId');
        $this->app->router->post('bill-by-table-id', 'RestaurantBillController@billByTableId');
        $this->app->router->delete('restaurants-bill/{id}', 'RestaurantBillController@destroy');
    }
);
