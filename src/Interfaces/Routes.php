<?php

namespace Spin\Interfaces;

use FastRoute\RouteCollector;

interface Routes
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
