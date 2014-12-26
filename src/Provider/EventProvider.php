<?php

namespace Spin\Provider;

use League;
use Spin\Event\Emitter;
use Spin\Provider;

class EventProvider extends Provider
{
    /**
     * @return void
     */
    public function bind()
    {
        $this->container->bindShared(
            "event.emitter",
            function () {
                return new Emitter(new League\Event\PriorityEmitter());
            }
        );
    }
}
