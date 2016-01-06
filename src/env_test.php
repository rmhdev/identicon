<?php

require_once __DIR__ . "/../vendor/autoload.php";

/* @var \Silex\Application $app */
$app = require __DIR__ . "/app.php";

$app["identicon.config"] = array();
$app["identicon.type"] = array_merge(
    $app["identicon.type"],
    array(
        "default" => "plain",
        "extra" => array("circle", "pyramid", "rhombus")
    )
);

return $app;
