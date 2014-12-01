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
        $collection = $this->app->resolve("route.collection");

        $collection->add("GET", "/", IndexHandler::class."@index");
    }
}
