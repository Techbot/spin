<?php

namespace Spin\Http;

use React\Http\Request as BaseRequest;
use Spin\Interfaces;

class Request extends BaseRequest implements Interfaces\Http\Request
{

    /**
     * @return string
     */
    public function method()
    {
        return $this->getMethod();
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->getPath();
    }
}