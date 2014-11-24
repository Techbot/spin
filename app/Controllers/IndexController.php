<?php

namespace App\Controllers;

use Spin\Traits;

class IndexController
{
    use Traits\ContainerDependency;

    /**
     * @return string
     */
    public function index()
    {
        return "Hello.";
    }
}
