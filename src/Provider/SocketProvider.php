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
     * @param callable $resolve
     */
    public function bind(callable $resolve)
    {
        $this->bindCollection($resolve);
        $this->bindBaseServer($resolve);
        $this->bindServer($resolve);
    }

    /**
     * @param callable $resolve
     */
    protected function bindCollection(callable $resolve)
    {
        $this->container->bindShared(
            "socket.collection",
            function () use ($resolve) {
                $sockets = new Collection();
                $sockets->container($this->container);

                return $sockets;
            }
        );
    }

    /**
     * @param callable $resolve
     */
    protected function bindBaseServer(callable $resolve)
    {
        $this->container->bind(
            "socket.base.server",
            function () use ($resolve) {
                return new BaseServer(
                    $resolve("loop")
                );
            }
        );
    }

    /**
     * @param callable $resolve
     */
    protected function bindServer(callable $resolve)
    {
        $this->container->bindShared(
            "socket.server",
            function () use ($resolve) {
                return new Server(
                    new HttpServer(
                        new WsServer(
                            $resolve("socket.collection")
                        )
                    ),
                    $resolve("socket.base.server"),
                    $resolve("loop")
                );
            }
        );
    }
}
