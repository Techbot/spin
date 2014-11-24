<?php

namespace Spin\Contracts;

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
