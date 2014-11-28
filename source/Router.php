<?php

namespace Spin;

use FastRoute;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

class Router implements Interfaces\Router
{
    use Traits\ContainerDependency;

    /**
     * @param string $method
     * @param string $uri
     *
     * @return array
     */
    public function dispatch($method, $uri)
    {
        $routes     = $this->getRoutes();
        $dispatcher = $this->getDispatcher($routes);

        return $this->handleDispatch($dispatcher, $method, $uri);
    }

    /**
     * @return Interfaces\Routes
     */
    protected function getRoutes()
    {
        return $this->container->resolve("routes");
    }

    /**
     * @param Interfaces\Routes $routes
     *
     * @return Dispatcher
     */
    protected function getDispatcher(Interfaces\Routes $routes)
    {
        return FastRoute\simpleDispatcher(function (RouteCollector $collector) use ($routes) {
            $routes->applyTo($collector);
        });
    }

    /**
     * @param Dispatcher $dispatcher
     * @param string     $method
     * @param string     $uri
     *
     * @return array
     */
    protected function handleDispatch(Dispatcher $dispatcher, $method, $uri)
    {
        $info = $dispatcher->dispatch($method, $uri);

        if ($info[0] === Dispatcher::NOT_FOUND) {
            return [
                "status" => 404,
            ];
        }

        if ($info[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            return [
                "status"  => 405,
                "methods" => $info[1],
            ];
        }

        if ($info[0] === Dispatcher::FOUND) {
            return [
                "status"     => 200,
                "handler"    => $info[1],
                "parameters" => $info[2],
            ];
        }

        return [
            "status" => 500,
        ];
    }
}
