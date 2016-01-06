<?php
/* @var \Silex\Application $app */

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    "twig.path" => __DIR__ . "/../templates/"
));

$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
    'http_cache.cache_dir'  => __DIR__ . "/../var/cache/",
    'http_cache.esi'        => null
));

$folder = __DIR__ . "/../config/";
$file = file_exists($folder . "parameters.json") ? "parameters.json" : "parameters.json.dist";
$app->register(new Igorw\Silex\ConfigServiceProvider($folder . $file), array());
