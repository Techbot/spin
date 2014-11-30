<?php

namespace App\Providers;

use App\Controllers\IndexController;
use Spin\Traits;

class RouteProvider
{
    use Traits\ContainerDependency;

    /**
     * @return void
     */
    public function bind()
    {
        $routes = $this->container->resolve("routes");

        $routes->add("GET", "/", IndexController::class."@index");
    }
}
