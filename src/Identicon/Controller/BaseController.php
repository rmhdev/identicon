<?php

namespace Identicon\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
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

    public function basicAction(Request $request, Application $app, $name, $format = "png")
    {
        $identicon = $this->createIdenticon($app, $name);
        $contentType = $this->getContentType($format);
        if (!$identicon || !$contentType) {
            return $this->createErrorResponse($app);
        }
        $filename = $identicon->getIdentity()->getName() . ".{$format}";

        return $this->createResponse($request, $identicon->getContent(), array(
            "Content-Type" => $contentType,
            "Content-Disposition" => 'inline; filename="' . $filename . '"'
        ));
    }

    protected function getContentType($format)
    {
        if (strcasecmp($format, "png") === 0) {
            return "image/png";
        }

        return "";
    }

    /**
     * @param Application $app
     * @param $name
     * @param null $type
     * @return \Identicon\AbstractIdenticon
     */
    protected function createIdenticon(Application $app, $name, $type = null)
    {
        if (!$type) {
            $type = $app["identicon.type"]["default"];
        }
        $class = sprintf('\Identicon\Type\%s\Identicon', ucfirst($type));
        if (!class_exists($class)) {
            return null;
        }

        return new $class($name, $app["identicon.config"]);
    }

    public function extraAction(Request $request, Application $app, $type, $name, $format = "png")
    {
        $identicon = $this->createIdenticon($app, $name, $type);
        $contentType = $this->getContentType($format);
        if (!$identicon || !$contentType) {
            return $this->createErrorResponse($app);
        }
        $filename = $identicon->getIdentity()->getName() . ".{$format}";

        return $this->createResponse($request, $identicon->getContent(), array(
            "Content-Type" => $contentType,
            "Content-Disposition" => 'inline; filename="' . $filename . '"'
        ));
    }

    public function profileAction(Request $request, Application $app, $name)
    {
        $identity = new Identity($name);
        $options = array(
            "name" => $identity->getName(),
            "types" => $app["identicon.type"]["extra"],
            "format" => "png"
        );

        return $this->createResponse($request, $app["twig"]->render("profile.twig", $options));
    }

    public function createErrorResponse(Application $app)
    {
        $options = array(
            "code" => 404
        );

        return new Response(
            $app["twig"]->render("error.twig", $options),
            404
        );
    }
}
