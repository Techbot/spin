<?php

namespace App;

use App\Providers\EventProvider;
use App\Providers\RouteProvider;
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
        ];
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return 4000;
    }
}
