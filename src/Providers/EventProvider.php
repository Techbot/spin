<?php

namespace Spin\Providers;

use League;
use Spin\Events;
use Spin\Provider;
use Spin\Traits;

class EventProvider extends Provider
{
    /**
     * @return void
     */
    public function bind()
    {
        $this->app->bindShared("events", function () {
            return new Events(new League\Event\PriorityEmitter);
        });
    }
}
