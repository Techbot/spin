<?php

namespace App\Provider;

use App\Socket\Handler\GithubHandler;
use App\Socket\Handler\TwitterHandler;
use Spin\Provider;

class SocketProvider extends Provider
{
    /**
     * @return void
     */
    public function bind()
    {
        $collection = $this->app->resolve("socket.collection");

        $collection->add(new GithubHandler());
        $collection->add(new TwitterHandler());
    }
}
