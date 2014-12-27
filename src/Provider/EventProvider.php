<?php

namespace Spin\Provider;

use League;
use Spin\Event\Emitter;
use Spin\Provider;

class EventProvider extends Provider
{
    /**
     * @param callable $resolve
     */
    public function bind(callable $resolve)
    {
        $this->container->bindShared(
            "event.emitter",
            function () {
                return new Emitter(new League\Event\PriorityEmitter());
            }
        );
    }
}
