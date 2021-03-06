<?php

namespace App\Socket\Handler;

use Exception;
use Spin\Socket\Handler;

class TwitterHandler extends Handler
{
    /**
     * @param mixed $connection
     */
    public function open($connection)
    {
        $connection->send("Twitter handler loaded");
    }

    /**
     * @param mixed $connection
     */
    public function close($connection)
    {
        // TODO
    }

    /**
     * @param mixed $connection
     * @param Exception $exception
     */
    public function error($connection, Exception $exception)
    {
        // TODO
    }

    /**
     * @param mixed $connection
     * @param string $message
     */
    public function message($connection, $message)
    {
        // TODO
    }
}
