<?php

namespace Spin\Interfaces\Socket;

use Exception;

interface Handler
{
    /**
     * @param Connection $connection
     *
     * @return void
     */
    public function open(Connection $connection);

    /**
     * @param Connection $connection
     *
     * @return void
     */
    public function close(Connection $connection);

    /**
     * @param Connection $connection
     * @param Exception  $exception
     *
     * @return void
     */
    public function error(Connection $connection, Exception $exception);

    /**
     * @param Connection $connection
     * @param string     $message
     *
     * @return void
     */
    public function message(Connection $connection, $message);
}
