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
        $this->app->bindShared("http.server", function () {
            return new Server(
                $this->app->resolve("socket.base.server")
            );
        });
    }
}
