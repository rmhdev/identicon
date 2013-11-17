<?php
/* @var $app \Silex\Application */
$app = require_once __DIR__ . "/production.php";

$app["debug"] = true;

return $app;
