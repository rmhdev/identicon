<?php

use \Symfony\Component\HttpFoundation\Response;
use Identicon\Identicon;

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

$app->get("/basic/{name}.png", function(\Silex\Application $app, $name) {
    $identicon = new Identicon($name);
    return new Response($identicon->getContent(), 200, array(
        "Content-Type" => "image/png"
    ));
});
