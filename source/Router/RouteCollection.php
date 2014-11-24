<?php

namespace Spin\Router;

use FastRoute\RouteCollector;
use Spin\Contracts;
use Spin\Traits;

class RouteCollection implements Contracts\Router\RouteCollection
{
    use Traits\ContainerDependency;

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @param string $method
     * @param string $pattern
     * @param mixed  $handler
     *
     * @return $this
     */
    public function add($method, $pattern, $handler)
    {
        array_push($this->routes, [$method, $pattern, $handler]);

        return $this;
    }

    /**
     * @param RouteCollector $collector
     *
     * @return $this
     */
    public function applyTo(RouteCollector $collector)
    {
        foreach ($this->routes as $route) {
            list($method, $pattern, $handler) = $route;

            $collector->addRoute($method, $pattern, $handler);
        }

        return $this;
    }
}
