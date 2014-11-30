<?php

namespace Spin\Event;

use League\Event\PriorityEmitter;
use Spin\Interfaces;

class Emitter implements Interfaces\Event\Emitter
{
    /**
     * @var PriorityEmitter
     */
    protected $emitter;

    /**
     * @param PriorityEmitter $emitter
     */
    public function __construct(PriorityEmitter $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * @param string   $key
     * @param callable $callable
     * @param int      $priority
     *
     * @return $this
     */
    public function listen($key, callable $callable, $priority = 0)
    {
        $this->emitter->addListener($key, $callable, $priority);

        return $this;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function emit($key)
    {
        call_user_func_array([$this->emitter, "emit"], func_get_args());

        return $this;
    }
}
