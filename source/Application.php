<?php

namespace Spin;

use Exception;
use LogicException;
use React;
use Symfony\Component\Debug\ExceptionHandler;

class Application
{
    use Traits\ContainerDependency;

    /**
     * @var array
     */
    protected $providers = [];

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
        $this->bindProviders();

        $this->container->resolve("events")->emit("app.before");

        $this->bindErrorHandling();

        $loop   = $this->container->resolve("loop");
        $socket = $this->container->resolve("socket.server");
        $server = $this->container->resolve("http.server");

        $router = $this->container->resolve("router");

        $server->on("request", function ($request, $response) use ($router) {
            $this->container->resolve("events")->emit("request.before", $request, $response);

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
                if (getenv("app.debug")) {
                    throw $exception;
                }

                $this->handleServerError($response);
            }

            $this->container->resolve("events")->emit("request.after", $request, $response);
        });

        $socket->listen(4000);
        $loop->run();

        $this->container->resolve("events")->emit("app.after");
    }

    /**
     * @return void
     */
    protected function bindProviders()
    {
        $this->createProviders();

        foreach ($this->providers as $instance) {
            if (method_exists($instance, "bind")) {
                $instance->bind();
            }
        }

        foreach ($this->providers as $instance) {
            if (method_exists($instance, "run")) {
                $instance->run();
            }
        }
    }

    /**
     * @return void
     */
    protected function createProviders()
    {
        $providers = [
            Providers\EventProvider::class,
            Providers\ReactProvider::class,
            Providers\RouteProvider::class,
        ];

        $providers = array_merge($providers, $this->blueprint->getProviders());

        foreach ($providers as $provider) {
            $this->providers[$provider] = new $provider;
        }
    }

    /**
     * @return string
     */
    protected function bindErrorHandling()
    {
        ExceptionHandler::register();

        ini_set("display_errors", 0);

        if (getenv("app.debug")) {
            ini_set("display_errors", 1);
        }
    }

    /**
     * @param array               $info
     * @param React\Http\Request  $request
     * @param React\Http\Response $response
     *
     * @return void
     */
    protected function handleAction(array $info, React\Http\Request $request, React\Http\Response $response)
    {
        $handler    = $info["handler"];
        $parameters = $info["parameters"];

        $parts   = explode("@", $handler);
        $handler = [new $parts[0], $parts[1]];

        if (!is_callable($handler)) {
            throw new LogicException("handler invalid");
        }

        $results = call_user_func($handler, $request, $response, $parameters);

        $response->writeHead(200, ["content-type" => "text/html"]);
        $response->end($results);
    }

    /**
     * @param React\Http\Response $response
     *
     * @return void
     */
    protected function handleNotFoundError(React\Http\Response $response)
    {
        // TODO

        $response->writeHead(404, ["content-type" => "text/plain"]);
        $response->end("Not found.");
    }

    /**
     * @param React\Http\Response $response
     *
     * @return void
     */
    protected function handleMethodError(React\Http\Response $response)
    {
        // TODO

        $response->writeHead(405, ["content-type" => "text/plain"]);
        $response->end("Method not allowed.");
    }

    /**
     * @param React\Http\Response $response
     *
     * @return void
     */
    protected function handleServerError(React\Http\Response $response)
    {
        // TODO

        $response->writeHead(500, ["content-type" => "text/plain"]);
        $response->end("Server error.");
    }
}
