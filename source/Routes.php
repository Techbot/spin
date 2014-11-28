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
     * @param string $pattern
     * @param string $handler
     *
     * @return $this
     */
    public function options($pattern, $handler)
    {
        return $this->add("OPTIONS", $pattern, $handler);
    }

    /**
     * @param string $pattern
     * @param string $handler
     *
     * @return $this
     */
    public function get($pattern, $handler)
    {
        return $this->add("GET", $pattern, $handler);
    }

    /**
     * @param string $pattern
     * @param string $handler
     *
     * @return $this
     */
    public function head($pattern, $handler)
    {
        return $this->add("HEAD", $pattern, $handler);
    }

    /**
     * @param string $pattern
     * @param string $handler
     *
     * @return $this
     */
    public function post($pattern, $handler)
    {
        return $this->add("POST", $pattern, $handler);
    }

    /**
     * @param string $pattern
     * @param string $handler
     *
     * @return $this
     */
    public function put($pattern, $handler)
    {
        return $this->add("PUT", $pattern, $handler);
    }

    /**
     * @param string $pattern
     * @param string $handler
     *
     * @return $this
     */
    public function patch($pattern, $handler)
    {
        return $this->add("PATCH", $pattern, $handler);
    }

    /**
     * @param string $pattern
     * @param string $handler
     *
     * @return $this
     */
    public function delete($pattern, $handler)
    {
        return $this->add("DELETE", $pattern, $handler);
    }

    /**
     * @param string $pattern
     * @param string $handler
     *
     * @return $this
     */
    public function trace($pattern, $handler)
    {
        return $this->add("TRACE", $pattern, $handler);
    }

    /**
     * @param string $pattern
     * @param string $handler
     *
     * @return $this
     */
    public function connect($pattern, $handler)
    {
        return $this->add("CONNECT", $pattern, $handler);
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
