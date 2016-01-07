<?php

require_once __DIR__ . "/../../../vendor/autoload.php";

/* @var \Silex\Application $app */
$app = require __DIR__ . "/../../../src/app.php";
require __DIR__ . "/../../../config/prod.php";
require __DIR__ . "/../../../src/providers.php";
require __DIR__ . "/../../../src/controllers.php";

$app["identicon.config"] = array();
$app["identicon.type"] = array_merge(
    $app["identicon.type"],
    array(
        "default" => "plain",
        "extra" => array("circle", "pyramid", "rhombus")
    )
);

return $app;
