<?php

use Silex\Application;

$app = new Application();

require __DIR__ . "/providers.php";
require __DIR__ . "/controllers.php";

return $app;
