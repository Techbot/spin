<?php

namespace App\Http\Handler;

class IndexHandler
{
    /**
     * @return string
     */
    public function index()
    {
        return file_get_contents("resources/views/index/index.html");
    }
}
