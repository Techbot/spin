<?php

namespace Spin\Providers;

use React;
use Spin\Provider;
use Spin\Traits;

class ReactProvider extends Provider
{
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
        $this->app->bindShared("loop", function () {
            return React\EventLoop\Factory::create();
        });
    }

    /**
     * @return void
     */
    protected function bindSocketServer()
    {
        $this->app->bindShared("socket.server", function () {
            return new React\Socket\Server(
                $this->app->resolve("loop")
            );
        });
    }

    /**
     * @return void
     */
    protected function bindHttpServer()
    {
        $this->app->bindShared("http.server", function () {
            return new React\Http\Server(
                $this->app->resolve("socket.server")
            );
        });
    }
}
