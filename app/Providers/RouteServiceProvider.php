<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;


class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $web = 'App\\Http\\Controllers';

    /**
     * @var string
     */
    protected $api = 'App\\Http\\Controllers\\Api';


    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapApiRoutes($router);
        $this->mapWebRoutes($router);
    }

    /**
     * @param Router $router
     */
    private function mapApiRoutes(Router $router)
    {
        $router->group(['middleware' => 'api', 'prefix' => 'api', 'namespace' => $this->api], function ($router) {
            require base_path('routes/api.php');
        });
    }

    /**
     * @param Router $router
     */
    private function mapWebRoutes(Router $router)
    {
        $router->group(['middleware' => 'web', 'namespace' => $this->web], function ($router) {
            require base_path('routes/web.php');
        });
    }
}
