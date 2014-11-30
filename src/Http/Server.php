<?php

namespace Spin\Http;

use React\Http\Server as HttpServer;
use React\Socket\ServerInterface as SocketServerInterface;
use Spin\Interfaces;

class Server extends HttpServer implements Interfaces\Http\Server
{
    /**
     * @var SocketServerInterface
     */
    protected $socket;

    /**
     * @param SocketServerInterface $socket
     *
     * @return Server
     */
    public function __construct(SocketServerInterface $socket)
    {
        parent::__construct($socket);

        $this->socket = $socket;
    }

    /**
     * @return SocketServerInterface
     */
    public function getSocket()
    {
        return $this->socket;
    }
}
