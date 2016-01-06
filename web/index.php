<?php
/* @var \Silex\Application $app */
//ini_set('display_errors', 0);
$app = require __DIR__ . "/../config/prod.php";
$app['http_cache']->run();
