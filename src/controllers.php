<?php

use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Request;
use Identicon\Identicon;
use Identicon\Identity\Identity;

/* @var \Silex\Application $app
 * @return Response
 */

$app->get("/", function(\Silex\Application $app) {
    return new Response(
        $app["twig"]->render("index.twig", array("error" => false)),
        200,
        array()
    );
})->bind("index");

$app->post("/", function(Request $request) use ($app) {
    $name = $request->request->get("name");
    if ($name) {
        return $app->redirect("/{$name}");
    }
    return new Response(
        $app["twig"]->render("index.twig", array("error" => $name ? false : true)),
        200,
        array()
    );
});

$app->get("/basic/{name}.png", function(\Silex\Application $app, $name) {
    $identicon = new Identicon($name);
    return new Response($identicon->getContent(), 200, array(
        "Content-Type" => "image/png"
    ));
})->bind("basic");

$app->get("/{name}", function(\Silex\Application $app, $name) {
    $identity = new Identity($name);
    return new Response(
        $app["twig"]->render("profile.twig", array(
            "name" => $identity->getName()
        )),
        200,
        array()
    );
});
