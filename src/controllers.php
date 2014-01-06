<?php

use \Symfony\Component\HttpFoundation\Response;

/* @var \Silex\Application $app
 * @return Response
 */

$app->match("/",
    'Identicon\Controller\BaseController::indexAction')->bind("index");

$app->get("/{name}.{format}",
    'Identicon\Controller\BaseController::basicAction')->bind("basic");

$app->get("/{name}/{type}.{format}",
    'Identicon\Controller\BaseController::extraAction')->bind("extra");

$app->get("/{name}",
    'Identicon\Controller\BaseController::profileAction')->bind("profile");
