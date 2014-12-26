<?php

namespace Spin\Interfaces;

interface Blueprint
{
    /**
     * @return array
     */
    public function providers();

    /**
     * @return string
     */
    public function httpHost();

    /**
     * @return int
     */
    public function httpPort();

    /**
     * @return string
     */
    public function socketHost();

    /**
     * @return int
     */
    public function socketPort();
}
