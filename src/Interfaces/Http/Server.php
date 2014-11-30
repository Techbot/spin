<?php

namespace Spin\Interfaces\Http;

interface Server
{
    /**
     * @return SocketServerInterface
     */
    public function getSocket();
}
