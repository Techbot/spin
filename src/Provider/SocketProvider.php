<?php

namespace Spin\Provider;

use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\Socket\Server as BaseServer;
use Spin\Provider;
use Spin\Socket\Collection;
use Spin\Socket\Server;

class SocketProvider extends Provider
{
    /**
     * @return void
     */
    public function bind()
    {
        $this->bindCollection();
        $this->bindBaseServer();
        $this->bindServer();
    }

    /**
     * @return void
     */
    protected function bindCollection()
    {
        $this->app->bindShared("socket.collection", function () {
            $sockets = new Collection();
            $sockets->setApplication($this->app);

            return $sockets;
        });
    }

    /**
     * @return void
     */
    protected function bindBaseServer()
    {
        $this->app->bind("socket.base.server", function () {
            return new BaseServer(
                $this->app->resolve("loop")
            );
        });
    }

    /**
     * @return void
     */
    protected function bindServer()
    {
        $this->app->bindShared("socket.server", function () {
            return new Server(
                new HttpServer(
                    new WsServer(
                        $this->app->resolve("socket.collection")
                    )
                ),
                $this->app->resolve("socket.base.server"),
                $this->app->resolve("loop")
            );
        });
    }
}
