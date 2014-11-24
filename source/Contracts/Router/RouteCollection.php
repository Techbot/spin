<?php

namespace Spin\Contracts\Router;

use FastRoute\RouteCollector;

interface RouteCollection
{
    /**
     * @param string $method
     * @param string $pattern
     * @param mixed  $handler
     *
     * @return $this
     */
    public function add($method, $pattern, $handler);

    /**
     * @param RouteCollector $collector
     *
     * @return $this
     */
    public function applyTo(RouteCollector $collector);
}
