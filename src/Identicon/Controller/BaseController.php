<?php

namespace Identicon\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Identicon\Type\Square\Identicon;
use Identicon\Identity\Identity;

/**
 * Based on https://igor.io/2012/11/09/scaling-silex.html
 *
 * Class BaseController
 * @package Identicon\Controller
 */
class BaseController
{
    public function indexAction(Request $request, Application $app)
    {
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
    }

    public function basicAction(Request $request, Application $app)
    {
        $name = $request->request->get("name");
        $identicon = new Identicon($name);
        $response = new Response($identicon->getContent(), 200, array(
            "Content-Type" => "image/png",
            "Cache-Control" => "public, max-age=3600, s-maxage=3600"
        ));
        $response->isNotModified($request);
        $response->setEtag(md5($response->getContent()));

        return $response;
    }

    public function extraAction(Request $request, $type, $name)
    {
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
    }

    public function profileAction(Request $request, Application $app, $name)
    {
        $identity = new Identity($name);
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
    }
}