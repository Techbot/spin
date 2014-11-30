<?php

namespace Spin\Providers;

use Spin\Provider;
use Spin\Router;
use Spin\Routes;
use Spin\Traits;

class RouteProvider extends Provider
{
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
        $this->app->bindShared("router", function () {
            $router = new Router;
            $router->setApplication($this->app);

            return $router;
        });
    }

    /**
     * @return void
     */
    protected function bindRoutes()
    {
        $this->app->bindShared("routes", function () {
            return new Routes;
        });
    }
}
