<?php

namespace Spin;

use Exception;
use LogicException;
use React;
use Simple;
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
    public function getBlueprint()
    {
        return $this->blueprint;
    }

    /**
     * @return void
     */
    public function run()
    {
        $this->bindProviders();

        $emitter = $this->resolve("event.emitter");

        $emitter->emit("app.before");

        $this->bindSocketServer();
        $this->bindHttpServer();

        $this->printHeader();

        $this->resolve("loop")->run();

        $emitter->emit("app.after");
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
            Provider\EventProvider::class,
            Provider\LoopProvider::class,
            Provider\HttpProvider::class,
            Provider\RouteProvider::class,
            Provider\SocketProvider::class,
            Provider\TemplateProvider::class,
        ];

        foreach ($this->blueprint->getProviders() as $provider) {
            array_push($providers, $provider);
        }

        foreach ($providers as $provider) {
            $instance = new $provider();

            if ($instance instanceof Interfaces\ContainerAware) {
                $instance->setContainer($this);
            }

            $this->providers->attach($instance);
        }
    }

    /**
     * @return void
     */
    protected function bindSocketServer()
    {
        $emitter = $this->resolve("event.emitter");

        $emitter->emit("socket.bind.before");

        $socket = $this->resolve("socket.server");

        $socket->getSocket()->listen(
            $this->blueprint->getSocketPort(),
            $this->blueprint->getSocketHost()
        );

        $emitter->emit("socket.bind.after");
    }

    /**
     * @return void
     */
    protected function bindHttpServer()
    {
        $emitter = $this->resolve("event.emitter");

        $emitter->emit("http.bind.before");

        $http = $this->resolve("http.server");

        $http->getSocket()->listen(
            $this->blueprint->getHttpPort(),
            $this->blueprint->getHttpHost()
        );

        $http->on("request", function ($request, $response) use ($emitter) {
            $emitter->emit("http.request.before", $request, $response);

            $this->handleRequest($request, $response);

            $emitter->emit("http.request.after", $request, $response);
        });

        $emitter->emit("http.bind.after");
    }

    protected function handleRequest(React\Http\Request $request, React\Http\Response $response)
    {
        try {
            $router = $this->resolve("router");

            $route = $router->resolve($request->getMethod(), $request->getPath());

            if ($router->status() == 404) {
                return $this->handleNotFoundError($response);
            }

            if ($router->status() == 405) {
                return $this->handleMethodError($response);
            }

            return $this->handleRoute($route, $request, $response);
        } catch (Exception $exception) {
            $this->handleServerError($response, $exception);
        }
    }

    /**
     * @param Simple\Interfaces\Route $route
     * @param React\Http\Request      $request
     * @param React\Http\Response     $response
     *
     * @return void
     */
    protected function handleRoute(Simple\Interfaces\Route $route, React\Http\Request $request, React\Http\Response $response)
    {
        $handler = $route->data();
        $parameters = $route->parameters();

        $parts = explode("@", $handler);

        $instance = new $parts[0]();

        if ($instance instanceof Interfaces\ContainerAware) {
            $instance->setContainer($this);
        }

        $handler = [$instance, $parts[1]];

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
        $template = $this->resolve("template");

        $response->writeHead(404, ["content-type" => "text/html"]);
        $response->end($template->render("error/missing"));
    }

    /**
     * @param React\Http\Response $response
     *
     * @return void
     */
    protected function handleMethodError(React\Http\Response $response)
    {
        $template = $this->resolve("template");

        $response->writeHead(405, ["content-type" => "text/html"]);
        $response->end($template->render("error/method"));
    }

    /**
     * @param React\Http\Response $response
     * @param Exception           $exception
     *
     * @return void
     */
    protected function handleServerError(React\Http\Response $response, Exception $exception)
    {
        $template = $this->resolve("template");

        if (getenv("app.debug")) {
            $markup = $template->render("error/server/advanced", compact("exception"));
        } else {
            $markup = $template->render("error/server/basic");
        }

        $response->writeHead(500, ["content-type" => "text/html"]);
        $response->end($markup);
    }

    /**
     * @return void
     */
    protected function printHeader()
    {
        $httpHost = $this->blueprint->getHttphost();
        $httpPort = $this->blueprint->getHttpPort();
        $socketHost = $this->blueprint->getSockethost();
        $socketPort = $this->blueprint->getSocketPort();

        $template = $this->resolve("template");

        print $template->render("console/header", compact("httpHost", "httpPort", "socketHost", "socketPort"));
    }
}
