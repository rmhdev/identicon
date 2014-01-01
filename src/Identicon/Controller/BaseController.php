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
        $options = array("error" => $name ? false : true);

        return $this->createResponse($request, $app["twig"]->render("index.twig", $options));
    }

    protected function createResponse(Request $request, $content, $headers = array())
    {
        $response = new Response(
            $content,
            200,
            array_merge($headers, array(
                "Cache-Control" => "public, max-age=3600, s-maxage=3600"
            ))
        );
        $response->isNotModified($request);
        $response->setEtag(md5($response->getContent()));

        return $response;
    }

    public function basicAction(Request $request, Application $app, $name)
    {
        $identicon = new Identicon($name, $app["identicon.config"]);

        return $this->createResponse($request, $identicon->getContent(), array("Content-Type" => "image/png"));
    }

    public function extraAction(Request $request, Application $app, $type, $name)
    {
        $class = sprintf('\Identicon\Type\%s\Identicon', ucfirst($type));
        if (!class_exists($class)) {
            return new Response("Error", 404);
        }
        $identicon = new $class($name, $app["identicon.config"]);
        /* @var \Identicon\AbstractIdenticon $identicon */

        return $this->createResponse($request, $identicon->getContent(), array("Content-Type" => "image/png"));
    }

    public function profileAction(Request $request, Application $app, $name)
    {
        $identity = new Identity($name);
        $options = array(
            "name" => $identity->getName(),
            "types" => array("pyramid", "circle", "rhombus")
        );

        return $this->createResponse($request, $app["twig"]->render("profile.twig", $options));
    }
}