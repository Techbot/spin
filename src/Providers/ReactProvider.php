<?php

namespace Spin\Providers;

use React\EventLoop\Factory as Loop;
use React\Http\Server as HttpServer;
use React\Socket\Server as SocketServer;
use Spin\Traits;

class ReactProvider
{
    use Traits\ContainerDependency;

    /**
     * @return void
     */
    public function bind()
    {
        $this->bindLoop();
        $this->bindSocketServer();
        $this->bindHttpServer();
    }

    /**
     * @return void
     */
    protected function bindLoop()
    {
        $this->container->bindShared("loop", function () {
            return Loop::create();
        });
    }

    /**
     * @return void
     */
    protected function bindSocketServer()
    {
        $this->container->bindShared("socket.server", function () {
            return new SocketServer(
                $this->container->resolve("loop")
            );
        });
    }

    /**
     * @return void
     */
    protected function bindHttpServer()
    {
        $this->container->bindShared("http.server", function () {
            return new HttpServer(
                $this->container->resolve("socket.server")
            );
        });
    }
}
