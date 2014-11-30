<?php

namespace Spin;

use Exception;
use LogicException;
use React;

class Application extends Container implements Interfaces\Application
{
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
     */
    public function __construct(Interfaces\Blueprint $blueprint)
    {
        parent::__construct();

        $this->blueprint = $blueprint;
    }

    /**
     * @return void
     */
    public function run()
    {
        $this->bindProviders();

        $this->resolve("events")->emit("app.before");

        $loop   = $this->resolve("loop");
        $socket = $this->resolve("socket.server");
        $server = $this->resolve("http.server");

        $router = $this->resolve("router");

        $server->on("request", function ($request, $response) use ($router) {
            $this->resolve("events")->emit("request.before", $request, $response);

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

            $this->resolve("events")->emit("request.after", $request, $response);
        });

        $port = $this->blueprint->getPort();

        $this->printHeader();

        $socket->listen($port);
        $loop->run();

        $this->resolve("events")->emit("app.after");
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
            $instance = new $provider;

            if ($instance instanceof Interfaces\ApplicationAware) {
                $instance->setApplication($this);
            }

            $this->providers[$provider] = $instance;
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

    protected function printHeader()
    {
        // http://patorjk.com/software/taag

        print "         _
 ___ ___|_|___
|_ -| . | |   |
|___|  _|_|_|_|
    |_|

Server at http://127.0.0.1:4000
";
    }
}
