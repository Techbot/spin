<?php

namespace Spin\Traits;

use Spin\Container;
use Spin\Contracts;

trait ContainerDependency
{
    /**
     * @var Contracts\Container
     */
    protected $container;

    /**
     * @param Contracts\Container|null $container
     */
    public function __construct(Contracts\Container $container = null)
    {
        $this->setContainerDependency($container);
    }

    /**
     * @param Contracts\Container|null $container
     *
     * @return $this
     */
    protected function setContainerDependency(Contracts\Container $container = null)
    {
        if ($container === null) {
            $container = Container::shared();
        }

        $this->container = $container;

        return $this;
    }
}
