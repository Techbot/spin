<?php

namespace Spin\Interfaces;

interface Container
{
    /**
     * @param string   $key
     * @param callable $factory
     *
     * @return $this
     */
    public function bind($key, callable $factory);

    /**
     * @param string   $key
     * @param callable $factory
     *
     * @return $this
     */
    public function bindShared($key, callable $factory);

    /**
     * @param string   $key
     * @param callable $factory
     *
     * @return $this
     */
    public function extend($key, callable $factory);

    /**
     * @param string $key
     *
     * @return $this
     */
    public function unbind($key);

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function resolve($key);
}
