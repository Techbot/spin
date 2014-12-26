<?php

namespace App\Socket\Handler;

use Exception;
use Spin\Socket\Handler;

class GithubHandler extends Handler
{
    /**
     * @param mixed $connection
     *
     * @return void
     */
    public function open($connection)
    {
        $connection->send("Github handler loaded");
    }

    /**
     * @param mixed $connection
     *
     * @return void
     */
    public function close($connection)
    {
        // TODO
    }

    /**
     * @param mixed $connection
     * @param Exception $exception
     *
     * @return void
     */
    public function error($connection, Exception $exception)
    {
        // TODO
    }

    /**
     * @param mixed $connection
     * @param string $message
     *
     * @return void
     */
    public function message($connection, $message)
    {
        // TODO
    }
}
