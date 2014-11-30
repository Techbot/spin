<?php

namespace Spin\Providers;

use League\Event\PriorityEmitter;
use Spin\Events;
use Spin\Traits;

class EventProvider
{
    use Traits\ContainerDependency;

    /**
     * @return void
     */
    public function bind()
    {
        $this->container->bindShared("events", function () {
            return new Events(new PriorityEmitter);
        });
    }
}
