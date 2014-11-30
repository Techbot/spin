<?php

namespace Spin\Interfaces;

interface Application extends Container
{
    /**
     * @return $this
     */
    public function run();
}
