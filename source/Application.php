<?php

namespace Spin;

use League\Event\PriorityEmitter;
use LogicException;
use React;

class Application
{
    use Traits\ContainerDependency;

    /**
     * @var Interfaces\Blueprint
     */
    protected $blueprint;

    /**
     * @param Interfaces\Blueprint $blueprint
     * @param Interfaces\Container $container
     */
    public function __construct(Interfaces\Blueprint $blueprint, Interfaces\Container $container = null)
    {
        $this->setContainerDependency($container);

        $this->blueprint = $blueprint;
    }

    /**
     * @return void
     */
    public function run()
    {
        $this->bindDependencies();
        $this->bindProviders();

        $events = $this->container->resolve("events");
        $events->emit("app/before");

        $loop   = $this->container->resolve("loop");
        $socket = $this->container->resolve("socket/server");
        $server = $this->container->resolve("http/server");

        $router = $this->container->resolve("router");

        $server->on("request", function ($request, $response) use ($events, $router) {
            $events->emit("request/before", $request, $response);

            try {
                $info = $router->dispatch(
                    $request->getMethod(),
                    $request->getPath()
                );

                if ($info["status"] === 200) {
                    $this->handleAction($info, $request, $response);
                }

                if ($info["status"] === 404) {
                    $this->handleNotFoundError($response);
                }

                if ($info["status"] === 405) {
                    $this->handleMethodError($response);
                }
            } catch (Exception $exception) {
                $this->handleServerError($response);
            }

            $events->emit("request/after", $request, $response);
        });

        $socket->listen(4000);
        $loop->run();

        $events->emit("app/after");
    }

    /**
     * @return $this
     */
    protected function bindDependencies()
    {
        $this->bindEvents();
        $this->bindRouter();
        $this->bindRoutes();
        $this->bindEventLoop();
        $this->bindSocket();
        $this->bindServer();

        return $this;
    }

    /**
     * @return $this
     */
    protected function bindRouter()
    {
        $this->container->bindShared("router", function () {
            return new Router;
        });

        return $this;
    }

    /**
     * @return $this
     */
    protected function bindRoutes()
    {
        $this->container->bindShared("routes", function () {
            return new Routes;
        });

        return $this;
    }

    /**
     * @return $this
     */
    protected function bindEvents()
    {
        $this->container->bindShared("events", function () {
            return new Events(new PriorityEmitter);
        });

        return $this;
    }

    /**
     * @return $this
     */
    protected function bindEventLoop()
    {
        $this->container->bindShared("loop", function () {
            return React\EventLoop\Factory::create();
        });

        return $this;
    }

    /**
     * @return $this
     */
    protected function bindSocket()
    {
        $this->container->bindShared("socket/server", function () {
            return new React\Socket\Server(
                $this->container->resolve("loop")
            );
        });

        return $this;
    }

    /**
     * @return $this
     */
    protected function bindServer()
    {
        $this->container->bindShared("http/server", function () {
            return new React\Http\Server(
                $this->container->resolve("socket/server")
            );
        });

        return $this;
    }

    /**
     * @return $this
     */
    protected function bindProviders()
    {
        $providers = $this->blueprint->getProviders();

        foreach ($providers as $provider) {
            $instances[$provider] = new $provider;
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

    /**
     * @param array               $info
     * @param React\Http\Request  $request
     * @param React\Http\Response $response
     *
     * @return $this
     */
    protected function handleAction(array $info, $request, $response)
    {
        $handler    = $info["handler"];
        $parameters = $info["parameters"];

        $parts   = explode("@", $handler);
        $handler = [new $parts[0], $parts[1]];

        if (!is_callable($handler)) {
            throw new LogicException("handler invalid");
        }

        $response->writeHead(200, ["content-type" => "text/html"]);
        $response->end(call_user_func($handler, $request, $response, $parameters));

        return $this;
    }

    /**
     * @param React\Http\Response $response
     *
     * @return $this
     */
    protected function handleNotFoundError($response)
    {
        // TODO

        $response->writeHead(404, ["content-type" => "text/plain"]);
        $response->end("Not found.");

        return $this;
    }

    /**
     * @param React\Http\Response $response
     *
     * @return $this
     */
    protected function handleMethodError($response)
    {
        // TODO

        $response->writeHead(405, ["content-type" => "text/plain"]);
        $response->end("Method not allowed.");

        return $this;
    }

    /**
     * @param React\Http\Response $response
     *
     * @return $this
     */
    protected function handleServerError($response)
    {
        // TODO

        $response->writeHead(500, ["content-type" => "text/plain"]);
        $response->end("Server error.");

        return $this;
    }
}
