<?php

namespace App;

use App\Provider\EventProvider;
use App\Provider\RouteProvider;
use App\Provider\SocketProvider;
use Spin\Interfaces;

class Blueprint implements Interfaces\Blueprint
{
    /**
     * @return array
     */
    public function getProviders()
    {
        return [
            EventProvider::class,
            RouteProvider::class,
            SocketProvider::class,
        ];
    }

    /**
     * @return string
     */
    public function getHttpHost()
    {
        return "127.0.0.1";
    }

    /**
     * @return int
     */
    public function getHttpPort()
    {
        return 4001;
    }

    /**
     * @return string
     */
    public function getSocketHost()
    {
        return "127.0.0.1";
    }

    /**
     * @return int
     */
    public function getSocketPort()
    {
        return 4002;
    }
}
