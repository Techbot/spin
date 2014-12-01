<?php

namespace Spin\Provider;

use Spin\Provider;
use Spin\Route\Collection;
use Spin\Route\Dispatcher;

class RouteProvider extends Provider
{
    /**
     * @return void
     */
    public function bind()
    {
        $this->bindDispatcher();
        $this->bindCollection();
    }

    /**
     * @return void
     */
    protected function bindDispatcher()
    {
        $this->app->bindShared("route.dispatcher", function () {
            $router = new Dispatcher();
            $router->setApplication($this->app);

            return $router;
        });
    }

    /**
     * @return void
     */
    protected function bindCollection()
    {
        $this->app->bindShared("route.collection", function () {
            return new Collection();
        });
    }
}
