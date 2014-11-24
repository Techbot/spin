<?php

require "vendor/autoload.php";

use App\Providers\RoutingProvider;
use Spin\Application;

$application = new Application();

$application->setProviders([
    RoutingProvider::class,
]);

$application->run();
