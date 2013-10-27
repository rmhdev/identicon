<?php

use \Symfony\Component\HttpFoundation\Response;

/* @var \Silex\Application $app
 * @return Response
 */

$app->get("/", function(\Silex\Application $app) {
    return new Response("Welcome to Identicon", 200);
});