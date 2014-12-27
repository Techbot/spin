<?php

namespace Spin\Provider;

use Spin\Http\Server;
use Spin\Provider;

class HttpProvider extends Provider
{
    /**
     * @param callable $resolve
     */
    public function bind(callable $resolve)
    {
        $this->container->bindShared(
            "http.server",
            function () {
                return new Server(
                    $resolve("socket.base.server")
                );
            }
        );
    }
}
