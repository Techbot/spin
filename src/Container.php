<?php

namespace Spin;

use Pimple;

class Container implements Interfaces\Container
{
    /**
     * @var array
     */
    protected $bound = [];

    /**
     * @var array
     */
    protected $shared = [];

    /**
     * @var Pimple\Container
     */
    protected $provider;

    /**
     * @return Container
     */
    public function __construct()
    {
        $this->provider = new Pimple\Container;
    }

    /**
     * @param string   $key
     * @param callable $factory
     *
     * @return $this
     */
    public function bind($key, callable $factory)
    {
        $this->provider[$key] = $this->provider->factory($factory);

        return $this;
    }

    /**
     * @param string   $key
     * @param callable $factory
     *
     * @return $this
     */
    public function bindShared($key, callable $factory)
    {
        $this->provider[$key] = $factory;

        return $this;
    }

    /**
     * @param string   $key
     * @param callable $factory
     *
     * @return $this
     */
    public function extend($key, callable $factory)
    {
        $this->provider->extend($key, $factory);

        return $this;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function unbind($key)
    {
        if (isset($this->provider[$key])) {
            unset($this->provider[$key]);
        }

        return $this;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function resolve($key)
    {
        if (isset($this->provider[$key])) {
            return $this->provider[$key];
        }

        return null;
    }
}
