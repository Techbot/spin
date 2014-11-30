<?php

namespace Spin\Interfaces\Route;

interface Dispatcher
{
    /**
     * @param string $method
     * @param string $uri
     *
     * @return array
     */
    public function dispatch($method, $uri);
}
