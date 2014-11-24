<?php

namespace app\Providers;

use App\Controllers\IndexController;
use Spin\Contracts;
use Spin\Traits;

class RoutingProvider
{
    use Traits\ContainerDependency;

    public function bind()
    {
        $this->container->extend(Contracts\Router\RouteCollection::class, function (Contracts\Router\RouteCollection $collection) {
            $collection->add("GET", "/", [IndexController::class, "index"]);

            return $collection;
        });
    }
}
