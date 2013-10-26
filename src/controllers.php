<?php

use \Symfony\Component\HttpFoundation\Response;

/* @var \Silex\Application $app */

$app->get("/", function(\Silex\Application $app) {
    return new Response("Hello world!", 200);
});