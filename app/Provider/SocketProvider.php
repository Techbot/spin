<?php

namespace App\Provider;

use App\Socket\Handler\GithubHandler;
use App\Socket\Handler\TwitterHandler;
use Spin\Provider;

class SocketProvider extends Provider
{
    /**
     * @param callable $resolve
     */
    public function bind(callable $resolve)
    {
        $collection = $resolve("socket.collection");

        $collection->add(new GithubHandler());
        $collection->add(new TwitterHandler());
    }
}
