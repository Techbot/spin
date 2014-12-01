<?php

namespace Spin\Interfaces\Socket;

use Exception;
use Spin\Interfaces;

interface Handler extends Interfaces\ApplicationAware
{
    /**
     * @param mixed $connection
     *
     * @return void
     */
    public function open($connection);

    /**
     * @param mixed $connection
     *
     * @return void
     */
    public function close($connection);

    /**
     * @param mixed     $connection
     * @param Exception $exception
     *
     * @return void
     */
    public function error($connection, Exception $exception);

    /**
     * @param mixed  $connection
     * @param string $message
     *
     * @return void
     */
    public function message($connection, $message);
}
