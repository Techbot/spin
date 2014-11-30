<?php

namespace Spin\Interfaces;

interface Blueprint
{
    /**
     * @return array
     */
    public function getProviders();

    /**
     * @return int
     */
    public function getPort();
}
