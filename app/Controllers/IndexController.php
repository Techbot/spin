<?php

namespace App\Controllers;

use Exception;
use Spin\Traits;

class IndexController
{
    use Traits\ContainerDependency;

    /**
     * @throws Exception
     * @return string
     */
    public function index()
    {
        throw new Exception;

        return "this is a quick page";
    }
}
