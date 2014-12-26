<?php

namespace App;

use App\Provider\RouteProvider;
use App\Provider\SocketProvider;
use Spin\Interfaces;

class Blueprint implements Interfaces\Blueprint
{
    /**
     * @return array
     */
    public function providers()
    {
        return [
            RouteProvider::class,
            SocketProvider::class,
        ];
    }

    /**
     * @return string
     */
    public function httpHost()
    {
        return "127.0.0.1";
    }

    /**
     * @return int
     */
    public function httpPort()
    {
        return 4001;
    }

    /**
     * @return string
     */
    public function socketHost()
    {
        return "127.0.0.1";
    }

    /**
     * @return int
     */
    public function socketPort()
    {
        return 4002;
    }
}
