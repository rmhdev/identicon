<?php

use \Symfony\Component\HttpFoundation\Response;

/* @var \Silex\Application $app
 * @return Response
 */

$app->get("/", function(\Silex\Application $app) {
    return new Response(
        $app["twig"]->render("index.twig", array()),
        200,
        array()
    );
});