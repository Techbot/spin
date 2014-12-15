<?php

namespace Spin\Socket;

use Spin\Interfaces;
use Spin\Traits;

abstract class Handler implements Interfaces\Socket\Handler
{
    use Traits\ContainerAware;
}
