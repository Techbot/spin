<?php

namespace Spin\Socket;

use Ratchet\Server\IoServer;
use React\Socket\ServerInterface;
use Spin\Interfaces;

class Server extends IoServer implements Interfaces\Socket\Server
{
    /**
     * @return ServerInterface
     */
    public function getSocket()
    {
        return $this->socket;
    }
}
