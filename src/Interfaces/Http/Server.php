<?php

namespace Spin\Interfaces\Http;

use React\Socket\ServerInterface;

interface Server
{
    /**
     * @return ServerInterface
     */
    public function getSocket();
}
