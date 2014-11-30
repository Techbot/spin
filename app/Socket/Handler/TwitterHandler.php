<?php

namespace App\Socket\Handler;

use Exception;
use Spin\Interfaces\Socket\Connection;
use Spin\Interfaces\Socket\Handler;

class TwitterHandler implements Handler
{
    /**
     * @param Connection $connection
     *
     * @return void
     */
    public function open(Connection $connection)
    {
        $connection->send("twitter handler loaded.");
    }

    /**
     * @param Connection $connection
     *
     * @return void
     */
    public function close(Connection $connection)
    {
        // TODO
    }

    /**
     * @param Connection $connection
     * @param Exception  $exception
     *
     * @return void
     */
    public function error(Connection $connection, Exception $exception)
    {
        // TODO
    }

    /**
     * @param Connection $connection
     * @param string     $message
     *
     * @return void
     */
    public function message(Connection $connection, $message)
    {
        // TODO
    }
}
