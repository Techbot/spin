<?php

namespace App\Provider;

use App\Http\Handler\IndexHandler;
use Spin\Provider;

class RouteProvider extends Provider
{
    /**
     * @param callable $resolve
     */
    public function bind(callable $resolve)
    {
        $router = $resolve("router");

        $router->bind("GET", "/", IndexHandler::class . "@index");
    }
}
