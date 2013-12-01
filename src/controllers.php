<?php

use \Symfony\Component\HttpFoundation\Response;
use Identicon\Identicon;
use Identicon\Identity\Identity;

/* @var \Silex\Application $app
 * @return Response
 */

$app->get("/", function(\Silex\Application $app) {
    return new Response(
        $app["twig"]->render("index.twig", array()),
        200,
        array()
    );
})->bind("index");

$app->get("/basic/{name}.png", function(\Silex\Application $app, $name) {
    $identicon = new Identicon($name);
    return new Response($identicon->getContent(), 200, array(
        "Content-Type" => "image/png"
    ));
})->bind("basic");

$app->get("{name}", function(\Silex\Application $app, $name) {
    $identity = new Identity($name);
    return new Response(
        $app["twig"]->render("profile.twig", array(
            "name" => $identity->getName()
        )),
        200,
        array()
    );
});
