<?php

namespace Spin\Tests;

use Mockery;
use PHPUnit_Framework_TestCase;

class Test extends PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function tearDown()
    {
        Mockery::close();
    }
}
