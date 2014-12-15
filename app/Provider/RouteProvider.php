<?php

namespace App\Provider;

use App\Http\Handler\IndexHandler;
use Spin\Provider;

class RouteProvider extends Provider
{
    /**
     * @return void
     */
    public function bind()
    {
        $router = $this->container->resolve("router");

        $router->bind("GET", "/", IndexHandler::class."@index");
    }
}
