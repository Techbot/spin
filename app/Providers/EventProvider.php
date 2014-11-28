<?php

namespace App\Providers;

use Dotenv;
use Spin\Interfaces;
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

        $this->bindEnvironment($events);
        $this->bindProfiling($events);
    }

    /**
     * @param Interfaces\Events $events
     *
     * @return void
     */
    protected function bindEnvironment(Interfaces\Events $events)
    {
        $events->listen("app/before", function ($event) {
            Dotenv::load($_SERVER["PWD"]);
        });
    }

    /**
     * @param Interfaces\Events $events
     *
     * @return void
     */
    protected function bindProfiling(Interfaces\Events $events)
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
