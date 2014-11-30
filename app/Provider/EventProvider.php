<?php

namespace App\Provider;

use Dotenv;
use Spin\Interfaces;
use Spin\Provider;

class EventProvider extends Provider
{
    /**
     * @return void
     */
    public function run()
    {
        $emitter = $this->app->resolve("event.emitter");

        $this->bindEnvironment($emitter);
        $this->bindProfiling($emitter);
    }

    /**
     * @param Interfaces\Event\Emitter $emitter
     *
     * @return void
     */
    protected function bindEnvironment(Interfaces\Event\Emitter $emitter)
    {
        $emitter->listen("app.before", function ($event) {
            Dotenv::load($_SERVER["PWD"]);
        });
    }

    /**
     * @param Interfaces\Event\Emitter $emitter
     *
     * @return void
     */
    protected function bindProfiling(Interfaces\Event\Emitter $emitter)
    {
        $time = null;

        $emitter->listen("request.before", function ($event, $request, $response) use (&$time) {
            $time = microtime(true);
        });

        $emitter->listen("request.after", function ($event, $request, $response) use (&$time) {
            $path   = $request->getPath();
            $time   = number_format(microtime(true) - $time, 5);
            $memory = memory_get_usage();

            print PHP_EOL . "{$path} → {$time} ms + {$memory} bytes";
        });
    }
}
