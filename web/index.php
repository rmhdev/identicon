<?php
/* @var \Silex\Application $app */

//ini_set('display_errors', 0);
require_once __DIR__ . "/../vendor/autoload.php";
$app = require __DIR__ . "/../src/app.php";
require __DIR__ . "/../config/prod.php";
require __DIR__ . "/../src/providers.php";
require __DIR__ . "/../src/controllers.php";

$app['http_cache']->run();
