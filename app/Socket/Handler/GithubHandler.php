<?php

namespace App\Socket\Handler;

use Exception;
use Spin\Interfaces\Socket\Connection;
use Spin\Interfaces\Socket\Handler;

class GithubHandler implements Handler
{
    /**
     * @param Connection $connection
     *
     * @return void
     */
    public function open(Connection $connection)
    {
        $connection->send("github handler loaded.");
    }

    /**
     * @param Connection $connection
     *
     * @return void
     */
    public function close(Connection $connection)
    {
        // TODO: Implement close() method.
    }

    /**
     * @param Connection $connection
     * @param Exception  $exception
     *
     * @return void
     */
    public function error(Connection $connection, Exception $exception)
    {
        // TODO: Implement error() method.
    }

    /**
     * @param Connection $connection
     * @param string     $message
     *
     * @return void
     */
    public function message(Connection $connection, $message)
    {
        // TODO: Implement message() method.
    }
}
