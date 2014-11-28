<?php

namespace Spin\Interfaces;

interface Router
{
    /**
     * @param string $method
     * @param string $uri
     *
     * @return array
     */
    public function dispatch($method, $uri);
}
