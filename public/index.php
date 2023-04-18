<?php

namespace Kuhlt\ErstiFragebogen;

use Kuhlt\ErstiFragebogen\Classes\Routing;

require __DIR__ . '/../vendor/autoload.php';

$routing = new Routing();
$routing->callActionByRoute();
