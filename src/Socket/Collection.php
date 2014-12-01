<?php

namespace Spin\Socket;

use Exception;
use Ratchet\ConnectionInterface;
use Spin\Interfaces;
use Spin\Traits;
use SplObjectStorage;

class Collection implements Interfaces\Socket\Collection, Interfaces\ApplicationAware
{
    use Traits\ApplicationAware;

    /**
     * @var SplObjectStorage
     */
    protected $connections;

    /**
     * @var SplObjectStorage
     */
    protected $handlers;

    /**
     * @return Collection
     */
    public function __construct()
    {
        $this->connections = new SplObjectStorage();
        $this->handlers    = new SplObjectStorage();
    }

    /**
     * @param ConnectionInterface $connection
     *
     * @return void
     */
    public function onOpen(ConnectionInterface $connection)
    {
        $this->emit("socket.open", [$connection]);

        $this->connections->attach($connection);
    }

    /**
     * @param string $key
     * @param array  $parameters
     *
     * @return void
     */
    protected function emit($key, array $parameters = [])
    {
        $emitter = $this->app->resolve("event.emitter");

        foreach ($this->handlers as $socket) {
            $id   = $this->handlers[$socket]["id"];
            $copy = $parameters;

            array_unshift($copy, "{$key}.{$id}");

            call_user_func_array([$emitter, "emit"], $copy);
        }
    }

    /**
     * @param ConnectionInterface $connection
     *
     * @return void
     */
    public function onClose(ConnectionInterface $connection)
    {
        $this->emit("socket.close", [$connection]);

        $this->connections->detach($connection);
    }

    /**
     * @param ConnectionInterface $connection
     * @param Exception           $exception
     *
     * @return void
     */
    public function onError(ConnectionInterface $connection, Exception $exception)
    {
        $this->emit("socket.error", [$connection, $exception]);

        $connection->close();
    }

    /**
     * @param ConnectionInterface $connection
     * @param string              $message
     *
     * @return void
     */
    public function onMessage(ConnectionInterface $connection, $message)
    {
        $this->emit("socket.message", [$connection, $message]);
    }

    /**
     * @param Interfaces\Socket\Handler $handler
     *
     * @return $this
     */
    public function add(Interfaces\Socket\Handler $handler)
    {
        static $id = 0;

        $this->handlers->attach($handler);

        if ($handler instanceof Interfaces\ApplicationAware) {
            $handler->setApplication($this->app);
        }

        $properties = ["id" => ++$id];

        $this->handlers[$handler] = $properties;

        print PHP_EOL."New socket: ".get_class($handler)." (#".$id.")";

        $emitter = $this->app->resolve("event.emitter");

        $emitter->listen("socket.open.{$id}", function ($event, $connection) use ($handler) {
            $handler->open($connection);
        });

        $emitter->listen("socket.close.{$id}", function ($event, $connection) use ($handler) {
            $handler->close($connection);
        });

        $emitter->listen("socket.error.{$id}", function ($event, $connection, Exception $exception) use ($handler) {
            $handler->error($connection, $exception);
        });

        $emitter->listen("socket.message.{$id}", function ($event, $connection, $message) use ($handler) {
            $handler->message($connection, $message);
        });

        return $this;
    }
}
