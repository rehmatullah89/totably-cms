<?php

/*
 * This file is part of the IdeaToLife package.
 *
 * (c) Youssef Jradeh <youssef.jradeh@ideatolife.me>
 *
 */

namespace App\Providers;

use App\Idea\Http\Middleware\CheckRequestType;
use App\Idea\Http\Middleware\ThrottleRequests;
use App\Idea\Http\Middleware\AdminAuthenticate;
use App\Idea\Http\Middleware\DBTransactionMiddleware;
use App\Idea\Http\Middleware\DeviceMiddleware;
use App\Idea\Http\Middleware\IPFilterMiddleware;
use App\Idea\Http\Middleware\AppVersionMiddleware;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Facades\Image;

class IdeaServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //JWT
        $this->app->register(\Tymon\JWTAuth\Providers\LumenServiceProvider::class);
        //Image handle
        $this->app->register(\Intervention\Image\ImageServiceProviderLumen::class);
        //Redis
        $this->app->register(\Illuminate\Redis\RedisServiceProvider::class);
        //CORS
        $this->app->register(\Nord\Lumen\Cors\CorsServiceProvider::class);
        $this->app->configure('cors');

        //Push Notification
//        $this->app->register(\LaravelFCM\FCMServiceProvider::class);
//        class_alias(\LaravelFCM\Facades\FCM::class, 'FCM');
        $this->app->configure('main');

        //EMAIL
        $this->app->singleton(
            'mailer',
            function ($app) {
                $app->configure('services');

                return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
            }
        );

        //IDE helper if you are not on the production
//        if ($this->app->environment() !== 'production') {
//            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
//        }
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->routeMiddleware(
            [
                'DeviceMiddleware' => DeviceMiddleware::class,
                'DBTransaction' => DBTransactionMiddleware::class,
                'AdminAuthenticate' => AdminAuthenticate::class,
                'throttle' => ThrottleRequests::class,
                'CheckRequestType' => CheckRequestType::class,
                'IPFilterMiddleware' => IPFilterMiddleware::class,
                'AppVersionMiddleware' => AppVersionMiddleware::class
            ]
        );
    }
}
