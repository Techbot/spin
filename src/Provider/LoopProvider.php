<?php

namespace Spin\Provider;

use React;
use Spin\Provider;

class LoopProvider extends Provider
{
    /**
     * @param callable $resolve
     */
    public function bind(callable $resolve)
    {
        $this->container->bindShared(
            "loop",
            function () {
                return React\EventLoop\Factory::create();
            }
        );
    }
}
