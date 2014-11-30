<?php

namespace Spin\Http;

use React\Http\Server as HttpServer;
use React\Socket\ServerInterface;
use Spin\Interfaces;

class Server extends HttpServer implements Interfaces\Http\Server
{
    /**
     * @var ServerInterface
     */
    protected $socket;

    /**
     * @param ServerInterface $socket
     *
     * @return Server
     */
    public function __construct(ServerInterface $socket)
    {
        parent::__construct($socket);

        $this->socket = $socket;
    }

    /**
     * @return ServerInterface
     */
    public function getSocket()
    {
        return $this->socket;
    }
}
