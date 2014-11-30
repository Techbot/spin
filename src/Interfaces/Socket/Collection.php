<?php

namespace Spin\Interfaces\Socket;

use Ratchet\MessageComponentInterface;

interface Collection extends MessageComponentInterface
{
    /**
     * @param Handler $handler
     *
     * @return $this
     */
    public function add(Handler $handler);
}
