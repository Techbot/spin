<?php

namespace Spin\Provider;

use React;
use Spin\Provider;

class LoopProvider extends Provider
{
    /**
     * @return void
     */
    public function bind()
    {
        $this->container->bindShared(
            "loop",
            function () {
                return React\EventLoop\Factory::create();
            }
        );
    }
}
