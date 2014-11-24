<?php

namespace Spin;

use Pimple;

class Container implements Contracts\Container
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
        $this->setProvider();
    }

    /**
     * @return $this
     */
    protected function setProvider()
    {
        $this->provider = new Pimple\Container();

        return $this;
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

    /**
     * @param string $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return isset($this->provider[$key]);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->resolve($key);
    }

    /**
     * @param mixed    $key
     * @param callable $value
     */
    public function offsetSet($key, $value)
    {
        $this->bind($key, $value);
    }

    /**
     * @param string $key
     */
    public function offsetUnset($key)
    {
        $this->unbind($key);
    }

    /**
     * @return static
     */
    public static function shared()
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new static();
        }

        return $instance;
    }
}
