<?php

require_once __DIR__ . "/../vendor/autoload.php";

/* @var \Silex\Application $app */
$app = require __DIR__ . "/app.php";

$app["debug"] = true;

return $app;
