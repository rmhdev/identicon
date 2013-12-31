<?php
/* @var \Silex\Application $app */

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    "twig.path" => __DIR__ . "/../templates/"
));

$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
    'http_cache.cache_dir'  => __DIR__ . "/../cache/",
    'http_cache.esi'        => null
));
