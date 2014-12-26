<?php

namespace Spin\Interfaces\Http;

interface Request
{
    /**
     * @return string
     */
    public function method();

    /**
     * @return string
     */
    public function path();
}