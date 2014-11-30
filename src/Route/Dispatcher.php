<?php

namespace Spin\Route;

use FastRoute;
use FastRoute\Dispatcher as FastRouteDispatcher;
use FastRoute\RouteCollector;
use Spin\Interfaces;
use Spin\Traits;

class Dispatcher implements Interfaces\Route\Dispatcher, Interfaces\ApplicationAware
{
    use Traits\ApplicationAware;

    /**
     * @param string $method
     * @param string $uri
     *
     * @return array
     */
    public function dispatch($method, $uri)
    {
        $routes     =  $this->app->resolve("route.collection");
        $dispatcher = $this->getDispatcher($routes);

        return $this->handleDispatch($dispatcher, $method, $uri);
    }

    /**
     * @param Interfaces\Route\Collection $routes
     *
     * @return FastRouteDispatcher
     */
    protected function getDispatcher(Interfaces\Route\Collection $routes)
    {
        return FastRoute\simpleDispatcher(function (RouteCollector $collector) use ($routes) {
            $routes->applyTo($collector);
        });
    }

    /**
     * @param FastRouteDispatcher $dispatcher
     * @param string              $method
     * @param string              $uri
     *
     * @return array
     */
    protected function handleDispatch(FastRouteDispatcher $dispatcher, $method, $uri)
    {
        $info = $dispatcher->dispatch($method, $uri);

        if ($info[0] === FastRouteDispatcher::NOT_FOUND) {
            return [
                "status" => 404,
            ];
        }

        if ($info[0] === FastRouteDispatcher::METHOD_NOT_ALLOWED) {
            return [
                "status"  => 405,
                "methods" => $info[1],
            ];
        }

        if ($info[0] === FastRouteDispatcher::FOUND) {
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
