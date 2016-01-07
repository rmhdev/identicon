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

$isDebug = isset($app['debug']) ? $app['debug'] : false;
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.level' => $isDebug ? \Monolog\Logger::DEBUG : \Monolog\Logger::ERROR,
    'monolog.logfile' =>  __DIR__ . "/../var/logs/" . ($isDebug ? 'dev.log' : 'prod.log'),
));

$folder = __DIR__ . "/../config/";
$file = file_exists($folder . "parameters.json") ? "parameters.json" : "parameters.dist.json";
$app->register(new Igorw\Silex\ConfigServiceProvider($folder . $file), array());
