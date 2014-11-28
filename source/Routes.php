<?php

namespace Spin;

use FastRoute\RouteCollector;
use Spin\Interfaces;
use Spin\Traits;

class Routes implements Interfaces\Routes
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
