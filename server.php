<?php

require "vendor/autoload.php";

$application = new Spin\Application(new App\Blueprint());
$application->run();
