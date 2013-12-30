<?php

namespace Identicon\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

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
}