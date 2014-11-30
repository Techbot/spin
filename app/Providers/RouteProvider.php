<?php

namespace App\Providers;

use App\Controllers\IndexController;
use Spin\Provider;
use Spin\Traits;

class RouteProvider extends Provider
{
    /**
     * @return void
     */
    public function bind()
    {
        $routes = $this->app->resolve("routes");

        $routes->add("GET", "/", IndexController::class . "@index");
    }
}
