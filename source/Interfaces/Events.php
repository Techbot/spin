<?php

namespace Spin\Interfaces;

interface Events
{
    /**
     * @param string   $key
     * @param callable $callable
     * @param int      $priority
     *
     * @return $this
     */
    public function listen($key, callable $callable, $priority = 0);

    /**
     * @param string $key
     *
     * @return $this
     */
    public function emit($key);
}
