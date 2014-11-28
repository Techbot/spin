<?php

namespace App\Providers;

use Spin\Contracts;
use Spin\Traits;

class EventProvider
{
    use Traits\ContainerDependency;

    /**
     * @return void
     */
    public function run()
    {
        $events = $this->container->resolve("events");

        $this->profile($events);
    }

    /**
     * @param $events
     *
     * @return void
     */
    protected function profile($events)
    {
        $time = null;

        $events->listen("request/before", function ($event, $request, $response) use (&$time) {
            $time = microtime(true);
        });

        $events->listen("request/after", function ($event, $request, $response) use (&$time) {
            $path   = $request->getPath();
            $time   = number_format(microtime(true) - $time, 5);
            $memory = memory_get_usage();

            print PHP_EOL . "{$path} → {$time} ms + {$memory} bytes";
        });
    }
}
