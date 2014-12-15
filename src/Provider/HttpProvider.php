<?php

namespace Spin\Provider;

use Spin\Http\Server;
use Spin\Provider;

class HttpProvider extends Provider
{
    /**
     * @return void
     */
    public function bind()
    {
        $this->container->bindShared("http.server", function () {
            return new Server(
                $this->container->resolve("socket.base.server")
            );
        });
    }
}
