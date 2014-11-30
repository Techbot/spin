<?php

namespace Spin\Traits;

use Spin\Container;
use Spin\Interfaces;

trait ContainerDependency
{
    /**
     * @var Interfaces\Container
     */
    protected $container;

    /**
     * @param Interfaces\Container|null $container
     */
    public function __construct(Interfaces\Container $container = null)
    {
        $this->setContainerDependency($container);
    }

    /**
     * @param Interfaces\Container|null $container
     *
     * @return $this
     */
    protected function setContainerDependency(Interfaces\Container $container = null)
    {
        if ($container === null) {
            $container = Container::shared();
        }

        $this->container = $container;

        return $this;
    }
}
