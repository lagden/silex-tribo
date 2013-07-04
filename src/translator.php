<?php
use Symfony\Component\Translation\Loader\YamlFileLoader;

$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());
    return $translator;
}));

$lang = "pt-BR";

if ($app['session']->get('current_language'))
    $lang = $app['session']->get('current_language');

foreach (glob(__DIR__ . "/locales/$lang.yml") as $locale)
    $app['translator']->addResource('yaml', $locale, $lang);

$app['translator']->setLocale($lang);
