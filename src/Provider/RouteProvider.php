<?php

namespace Spin\Provider;

use Simple\Router;
use Spin\Provider;

class RouteProvider extends Provider
{
    /**
     * @return void
     */
    public function bind()
    {
        $this->container->bindShared(
            "router",
            function () {
                return new Router();
            }
        );
    }
}
