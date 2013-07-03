<?php
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\MonologServiceProvider;


$app = new Application();

$app['title'] = "Tribo Interactive";

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

// Routes
require __DIR__ . '/routes.php';
require __DIR__ . '/menu.php';

return $app;