<?php
/* @var \Silex\Application $app */
//ini_set('display_errors', 0);
$app = require __DIR__ . "/../src/env_production.php";
$app['http_cache']->run();
