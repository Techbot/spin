<?php

namespace Spin\Traits;

use Spin\Interfaces;

trait ApplicationAware
{
    /**
     * @var Interfaces\Application
     */
    protected $app;

    /**
     * @param Interfaces\Application $app
     *
     * @return $this
     */
    public function setApplication(Interfaces\Application $app)
    {
        $this->app = $app;

        return $this;
    }
}
