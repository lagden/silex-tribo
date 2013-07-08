<?php
use Silex\Application;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use \Twig_Extension_Debug;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use CHH\Silex\CacheServiceProvider;

$app = new Application();

$app->register(new SessionServiceProvider());
$app->register(new HttpCacheServiceProvider());
$app->register(new UrlGeneratorServiceProvider());

$app->register(new TwigServiceProvider(), array(
    'twig.path'    => [ __DIR__.'/views' ],
    'twig.options' => [ 'cache' => __DIR__.'/../cache/twig', 'debug' => $debug ],
));

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addExtension(new Twig_Extension_Debug());
    return $twig;
}));

$app->register(new TranslationServiceProvider(), array(
    'locale' => 'pt-BR',
    'locale_fallback' => 'pt-BR',
));

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../log/app.log',
    'monolog.name'    => 'app',
    'monolog.level'   => 300
));

$app->register(new CacheServiceProvider, array(
    'cache.options' => array("default" => array(
        // "driver" => "apc",
        'driver' => 'filesystem',
        'directory' => sys_get_temp_dir() . '/site-tribo',
    ))
));

// Dados, Routes e Menu
require __DIR__ . '/dados.php';
require __DIR__ . '/translator.php';
require __DIR__ . '/routes.php';
require __DIR__ . '/menu.php';

return $app;