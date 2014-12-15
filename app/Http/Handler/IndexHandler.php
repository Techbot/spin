<?php

namespace App\Http\Handler;

use Spin\Http\Handler;

class IndexHandler extends Handler
{
    /**
     * @return string
     */
    public function index()
    {
        return $this->container->resolve("template")->render("index/index");
    }
}
