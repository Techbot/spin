<?php

namespace Spin;

use React;
use Spin\Interfaces;
use SplObjectStorage;

class Application extends Container implements Interfaces\Container
{
    /**
     * @var SplObjectStorage
     */
    protected $providers;

    /**
     * @var Interfaces\Blueprint
     */
    protected $blueprint;

    /**
     * @param Interfaces\Blueprint $blueprint
     */
    public function __construct(Interfaces\Blueprint $blueprint)
    {
        parent::__construct();

        $this->providers = new SplObjectStorage();
        $this->blueprint = $blueprint;
    }

    /**
     * @return Interfaces\Blueprint
     */
    public function blueprint()
    {
        return $this->blueprint;
    }

    public function run()
    {
        $this->bindProviders();

        $this->resolve("event.emitter")->emit("app.before");

        $this->bindSocketServer();
        $this->bindHttpServer();

        $this->resolve("loop")->run();

        $this->resolve("event.emitter")->emit("app.after");
    }

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

    protected function createProviders()
    {
        $providers = [
            Provider\ErrorProvider::class,
            Provider\EventProvider::class,
            Provider\LoopProvider::class,
            Provider\HttpProvider::class,
            Provider\RouteProvider::class,
            Provider\SocketProvider::class,
            Provider\TemplateProvider::class,
        ];

        foreach ($this->blueprint->providers() as $provider) {
            array_push($providers, $provider);
        }

        foreach ($providers as $provider) {
            $instance = new $provider();

            if ($instance instanceof Interfaces\ContainerAware) {
                $instance->container($this);
            }

            $this->providers->attach($instance);
        }
    }

    protected function bindSocketServer()
    {
        $this->resolve("event.emitter")->emit("socket.bind.before");

        $socket = $this->resolve("socket.server");

        $socket->socket()->listen(
            $this->blueprint->socketPort(),
            $this->blueprint->socketHost()
        );

        $this->resolve("event.emitter")->emit("socket.bind.after");
    }

    protected function bindHttpServer()
    {
        $this->resolve("event.emitter")->emit("http.bind.before");

        $http = $this->resolve("http.server");

        $http->socket()->listen(
            $this->blueprint->httpPort(),
            $this->blueprint->httpHost()
        );

        $http->on(
            "request",
            function ($request, $response) {
                $this->resolve("event.emitter")->emit("http.request.before", $request, $response);

                $this->handleRequest($request, $response);

                $this->resolve("event.emitter")->emit("http.request.after", $request, $response);
            }
        );

        $this->resolve("event.emitter")->emit("http.bind.after");
    }

    /**
     * @param Interfaces\Http\Request  $request
     * @param Interfaces\Http\Response $response
     */
    protected function handleRequest($request, $response)
    {
        try {
            $router = $this->resolve("router");

            $route = $router->resolve($request->getMethod(), $request->getPath());

            if ($router->status() == 404) {
                $this->handleMissingError($response);
            } else {
                if ($router->status() == 405) {
                    $this->handleMethodError($response);
                } else {
                    $this->handleRoute($route, $request, $response);
                }
            }
        } catch (Exception $exception) {
            $this->handleServerError($response, $exception);
        }
    }

    /**
     * @param Interfaces\Http\Response $response
     */
    protected function handleMissingError($response)
    {
        $handler = $this->resolve("error.missing");
        /** @var callable $handler */
        $handler($response);
    }

    /**
     * @param Interfaces\Http\Response $response
     */
    protected function handleMethodError($response)
    {
        $handler = $this->resolve("error.method");
        /** @var callable $handler */
        $handler($response);
    }

    /**
     * @param Interfaces\Router\Route  $route
     * @param Interfaces\Http\Request  $request
     * @param Interfaces\Http\Response $response
     *
     * @throws Exception
     */
    protected function handleRoute(
        $route,
        $request,
        $response
    ) {
        $handler = $route->data();
        $parameters = $route->parameters();

        $parts = explode("@", $handler);

        $instance = new $parts[0]();

        if ($instance instanceof Interfaces\ContainerAware) {
            $instance->container($this);
        }

        $handler = [$instance, $parts[1]];

        if (!is_callable($handler)) {
            throw new Exception("handler invalid");
        }

        $results = call_user_func($handler, $request, $response, $parameters);

        $response->writeHead(200, ["content-type" => "text/html"]);
        $response->end($results);
    }

    /**
     * @param Interfaces\Http\Response $response
     * @param Exception                $exception
     */
    protected function handleServerError($response, Exception $exception)
    {
        $handler = $this->resolve("error.server");
        /** @var callable $handler */
        $handler($response, $exception);
    }
}
