<?php

namespace Spin\Interfaces\Socket;

use Exception;
use Spin\Interfaces;

interface Handler extends Interfaces\ContainerAware
{
    /**
     * @param mixed $connection
     */
    public function open($connection);

    /**
     * @param mixed $connection
     */
    public function close($connection);

    /**
     * @param mixed $connection
     * @param Exception $exception
     */
    public function error($connection, Exception $exception);

    /**
     * @param mixed $connection
     * @param string $message
     */
    public function message($connection, $message);
}
