<?php

use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Request;
use Identicon\Identity\Identity;
use \Identicon\Type\Square\Identicon;

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
    $identicon = new Identicon($name);
    $response = new Response($identicon->getContent(), 200, array(
        "Content-Type" => "image/png",
        "Cache-Control" => "public, max-age=3600, s-maxage=3600"
    ));
    $response->isNotModified($request);
    $response->setEtag(md5($response->getContent()));

    return $response;
})->bind("basic");

$app->get("/{name}/{type}.png", function(Request $request, $name, $type) use ($app) {
    $class = sprintf('\Identicon\Type\%s\Identicon', ucfirst($type));
    if (!class_exists($class)) {
        return new Response("Error", 404);
    }
    $identicon = new $class($name);
    /* @var \Identicon\AbstractIdenticon $identicon */
    $response = new Response($identicon->getContent(), 200, array(
        "Content-Type" => "image/png",
        "Cache-Control" => "public, max-age=3600, s-maxage=3600"
    ));
    $response->isNotModified($request);
    $response->setEtag(md5($response->getContent()));

    return $response;
})->bind("extra");

$app->get("/{name}", function(Request $request, $name) use ($app) {
    $identity = new Identity($name);

    //print_r($identity->__toString()); die();

    $response = new Response(
        $app["twig"]->render("profile.twig", array(
            "name" => $identity->getName(),
            "types" => array("pyramid", "circle", "rhombus")
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
