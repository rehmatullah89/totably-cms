<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';


    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->router->get(
            '/',
            function () {
                echo 'It works';
            }
        );

        //App routes
        $this->mapPublicRoutes();
        $this->mapPrivateRoutes();

        //Admin Routes
        $this->mapAdminPublicRoutes();
        $this->mapAdminPrivateRoutes();
    }

    protected function mapPublicRoutes()
    {
        $this->app->router->group(
            [
                'middleware' => ['DeviceMiddleware', 'DBTransaction'],
                'namespace' => $this->namespace,
            ],
            function () {
                require base_path('routes/app/public.php');
            }
        );
    }

    protected function mapPrivateRoutes()
    {
        $this->app->router->group(
            [
                'middleware' => ['DeviceMiddleware', 'DBTransaction'],
                'namespace' => $this->namespace,
            ],
            function () {
                require base_path('routes/app/private.php');
            }
        );
    }

    protected function mapAdminPublicRoutes()
    {
        $this->app->router->group(
            [
                'middleware' => ['DeviceMiddleware', 'DBTransaction'],
                'namespace' => $this->namespace,
            ],
            function () {
                require base_path('routes/admin/public.php');
            }
        );
    }

    protected function mapAdminPrivateRoutes()
    {
        $this->app->router->group(
            [
                'middleware' => ['AdminAuthenticate', 'DeviceMiddleware', 'DBTransaction'],
                'namespace' => $this->namespace,
            ],
            function () {
                require base_path('routes/admin/private.php');
            }
        );
    }
}
