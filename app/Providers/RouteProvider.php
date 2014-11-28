<?php

namespace App\Providers;

use App\Controllers\IndexController;
use Spin\Contracts;
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

        $index = IndexController::class;

        $routes->add("GET", "/", "{$index}@index");
    }
}
