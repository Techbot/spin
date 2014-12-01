<?php

namespace Spin;

use Exception;
use LogicException;
use React;
use SplObjectStorage;

class Application extends Container implements Interfaces\Application
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

            if ($instance instanceof Interfaces\ApplicationAware) {
                $instance->setApplication($this);
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

            $this->handleRequest($this->resolve("route.dispatcher"), $request, $response);

            $emitter->emit("http.request.after", $request, $response);
        });

        $emitter->emit("http.bind.after");
    }

    /**
     * @param Route\Dispatcher    $router
     * @param React\Http\Request  $request
     * @param React\Http\Response $response
     *
     * @return void
     *
     * @throws Exception
     */
    protected function handleRequest(Route\Dispatcher $router, React\Http\Request $request, React\Http\Response $response)
    {
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
            $this->handleServerError($response, $exception);
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

        $parts = explode("@", $handler);

        $instance = new $parts[0]();

        if ($instance instanceof Interfaces\ApplicationAware) {
            $instance->setApplication($this);
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
        $httpHost   = $this->blueprint->getHttphost();
        $httpPort   = $this->blueprint->getHttpPort();
        $socketHost = $this->blueprint->getSockethost();
        $socketPort = $this->blueprint->getSocketPort();

        $template = $this->resolve("template");

        print $template->render("console/header", compact("httpHost", "httpPort", "socketHost", "socketPort"));
    }
}
