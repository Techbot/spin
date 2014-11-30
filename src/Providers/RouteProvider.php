<?php

namespace Spin\Providers;

use Spin\Router;
use Spin\Routes;
use Spin\Traits;

class RouteProvider
{
    use Traits\ContainerDependency;

    /**
     * @return void
     */
    public function bind()
    {
        $this->bindRouter();
        $this->bindRoutes();
    }

    /**
     * @return void
     */
    protected function bindRouter()
    {
        $this->container->bindShared("router", function () {
            return new Router;
        });
    }

    /**
     * @return void
     */
    protected function bindRoutes()
    {
        $this->container->bindShared("routes", function () {
            return new Routes;
        });
    }
}
