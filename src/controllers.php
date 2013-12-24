<?php

use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Request;
use Identicon\Identity\Identity;

/* @var \Silex\Application $app
 * @return Response
 */

$app->match("/", function(Request $request) use ($app) {
    $name = $request->request->get("name");
    if ($name) {
        return $app->redirect("/{$name}");
    }
    $response = new Response(
        $app["twig"]->render("index.twig", array("error" => $name ? false : true)),
        200,
        array(
            "Cache-Control" => "public, max-age=3600, s-maxage=3600"
        )
    );
    $response->isNotModified($request);
    $response->setEtag(md5($response->getContent()));

    return $response;
})->bind("index");

$app->get("/{name}.png", function(Request $request, $name) use ($app) {
    $identicon = new \Identicon\Types\Square\Identicon($name);
    $response = new Response($identicon->getContent(), 200, array(
        "Content-Type" => "image/png",
        "Cache-Control" => "public, max-age=3600, s-maxage=3600"
    ));
    $response->isNotModified($request);
    $response->setEtag(md5($response->getContent()));

    return $response;
})->bind("basic");

$app->get("/{name}", function(Request $request, $name) use ($app) {
    $identity = new Identity($name);
    $response = new Response(
        $app["twig"]->render("profile.twig", array(
            "name" => $identity->getName()
        )),
        200,
        array(
            "Cache-Control" => "public, max-age=3600, s-maxage=3600"
        )
    );
    $response->isNotModified($request);
    $response->setEtag(md5($response->getContent()));

    return $response;
});
