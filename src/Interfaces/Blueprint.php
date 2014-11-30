<?php

namespace Spin\Interfaces;

interface Blueprint
{
    /**
     * @return array
     */
    public function getProviders();

    /**
     * @return string
     */
    public function getHttpHost();

    /**
     * @return int
     */
    public function getHttpPort();

    /**
     * @return string
     */
    public function getSocketHost();

    /**
     * @return int
     */
    public function getSocketPort();
}
