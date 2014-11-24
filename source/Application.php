<?php

namespace Spin;

use React;

class Application
{
    use Traits\ContainerDependency;

    /**
     * @var array
     */
    protected $providers = [];

    /**
     * @param array $providers
     *
     * @return $this
     */
    public function setProviders(array $providers)
    {
        $this->providers = $providers;

        return $this;
    }

    /**
     * @return void
     */
    public function run()
    {
        $this->bindDefaultRouter();
        $this->bindDefaultRouteCollection();
        $this->bindDefaultEventLoop();
        $this->bindDefaultSocket();
        $this->bindDefaultServer();

        $this->bindProviders();

        $router = $this->container[Contracts\Router::class];

        $loop   = $this->container[React\EventLoop\LoopInterface::class];
        $socket = $this->container[React\Socket\ServerInterface::class];
        $server = $this->container[React\Http\ServerInterface::class];

        $server->on("request", function ($request, $response) use ($router) {
            try {
                $info = $router->dispatch(
                    $request->getMethod(),
                    $request->getPath()
                );

                if ($info["status"] === 200) {
                    $handler    = $info["handler"];
                    $parameters = $info["parameters"];

                    if (class_exists($info["handler"][0])) {
                        $handler[0] = new $handler[0]();
                    }

                    $response->writeHead(200, ["content-type" => "text/html"]);
                    $response->end(call_user_func($handler, $request, $response, $parameters));
                }

                if ($info["status"] === 404) {
                    // TODO

                    $response->writeHead(404, ["content-type" => "text/plain"]);
                    $response->end("Not found.");
                }

                if ($info["status"] === 405) {
                    // TODO

                    $response->writeHead(405, ["content-type" => "text/plain"]);
                    $response->end("Method not allowed.");
                }
            } catch (Exception $exception) {
                // TODO

                $response->writeHead(500, ["content-type" => "text/plain"]);
                $response->end("Server error.");
            }
        });

        $socket->listen(4000);
        $loop->run();
    }

    /**
     * @return $this
     */
    protected function bindDefaultRouter()
    {
        $this->container->bindShared(Contracts\Router::class, function () {
            return new Router();
        });

        return $this;
    }

    /**
     * @return $this
     */
    protected function bindDefaultRouteCollection()
    {
        $this->container->bindShared(Contracts\Router\RouteCollection::class, function () {
            return new Router\RouteCollection();
        });

        return $this;
    }

    /**
     * @return $this
     */
    protected function bindDefaultEventLoop()
    {
        $this->container->bindShared(React\EventLoop\LoopInterface::class, function () {
            return React\EventLoop\Factory::create();
        });

        return $this;
    }

    /**
     * @return $this
     */
    protected function bindDefaultSocket()
    {
        $this->container->bindShared(React\Socket\ServerInterface::class, function () {
            return new React\Socket\Server(
                $this->container[React\EventLoop\LoopInterface::class]
            );
        });

        return $this;
    }

    /**
     * @return $this
     */
    protected function bindDefaultServer()
    {
        $this->container->bindShared(React\Http\ServerInterface::class, function () {
            return new React\Http\Server(
                $this->container[React\Socket\ServerInterface::class]
            );
        });

        return $this;
    }

    /**
     * @return $this
     */
    protected function bindProviders()
    {
        foreach ($this->providers as $provider) {
            $instances[$provider] = new $provider();
        }

        foreach ($instances as $instance) {
            if (method_exists($instance, "bind")) {
                $instance->bind();
            }
        }

        foreach ($instances as $instance) {
            if (method_exists($instance, "run")) {
                $instance->run();
            }
        }

        return $this;
    }
}
