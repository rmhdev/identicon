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

$env = getenv('APP_ENV') ?: 'prod';
$folder = __DIR__ . "/../config/";
$file = file_exists($folder . "$env.json") ? "$env.json" : "$env.json.dist";
$app->register( new Igorw\Silex\ConfigServiceProvider($folder . $file), array(

));
