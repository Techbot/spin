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
        $this->container->bindShared("socket.collection", function () {
            $sockets = new Collection();
            $sockets->setContainer($this->container);

            return $sockets;
        });
    }

    /**
     * @return void
     */
    protected function bindBaseServer()
    {
        $this->container->bind("socket.base.server", function () {
            return new BaseServer(
                $this->container->resolve("loop")
            );
        });
    }

    /**
     * @return void
     */
    protected function bindServer()
    {
        $this->container->bindShared("socket.server", function () {
            return new Server(
                new HttpServer(
                    new WsServer(
                        $this->container->resolve("socket.collection")
                    )
                ),
                $this->container->resolve("socket.base.server"),
                $this->container->resolve("loop")
            );
        });
    }
}
