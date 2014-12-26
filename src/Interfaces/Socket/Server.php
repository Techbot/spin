<?php

namespace Spin\Interfaces\Socket;

use React\Socket\ServerInterface;

interface Server
{
    /**
     * @return ServerInterface
     */
    public function socket();
}
