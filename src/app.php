<?php
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\MonologServiceProvider;

$app = new Application();

$app->register(new HttpCacheServiceProvider());
$app->register(new UrlGeneratorServiceProvider());

$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(__DIR__.'/views'),
    'twig.options' => array('cache' => __DIR__.'/../cache/twig', 'debug' => $debug),
));

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../log/app.log',
    'monolog.name'    => 'app',
    'monolog.level'   => 300
));

// Dados, Routes e Menu
require __DIR__ . '/dados.php';
require __DIR__ . '/routes.php';
require __DIR__ . '/menu.php';

return $app;