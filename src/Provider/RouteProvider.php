<?php

namespace Spin\Provider;

use Simple\Router;
use Spin\Provider;

class RouteProvider extends Provider
{
    /**
     * @param callable $resolve
     */
    public function bind(callable $resolve)
    {
        $this->container->bindShared(
            "router",
            function () {
                return new Router();
            }
        );
    }
}
