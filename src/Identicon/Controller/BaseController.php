<?php

namespace Identicon\Controller;

use Identicon\Exception\InvalidArgumentException;
use Identicon\IdenticonFactory;
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
        return $this->createImageResponse($request, $app, $name, $format);
    }

    private function createImageResponse(Request $request, Application $app, $name, $format = "png", $type = null)
    {
        try {
            $identicon = $this->createIdenticon($app, $name, $type);
            $contentType = $this->getContentType($format);
        } catch (\Exception $e) {
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

        throw new InvalidArgumentException(sprintf('Unsupported image type "%s"', $format));
    }

    /**
     * @param Application $app
     * @param $name
     * @param null $type
     * @return \Identicon\IdenticonInterface
     */
    protected function createIdenticon(Application $app, $name, $type = null)
    {
        if (!$type) {
            $type = $app["identicon.type"]["default"];
        }

        return IdenticonFactory::create($type, $name, $app["identicon.config"]);
    }

    public function extraAction(Request $request, Application $app, $type, $name, $format = "png")
    {
        return $this->createImageResponse($request, $app, $name, $format, $type);
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
