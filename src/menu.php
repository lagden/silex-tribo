<?php
use Silex\Application;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Silex\KnpMenuServiceProvider;
use Knp\Menu\Silex\Voter\RouteVoter;

$app->register(new KnpMenuServiceProvider());
$app['tribo_menu'] = function($app) {

    $menu = $app['knp_menu.factory']->createItem('root', ['childrenAttributes' => ['class' => 'nav']]);
    $menu->addChild('Home', ['route' => 'homepage']);
    $menu->addChild('Trabalhos', ['route' => 'trabalho']);
    return $menu;
};

$app['tribo.voter'] = $app->share(function (Application $app) {
    $voter = new RouteVoter();
    $voter->setRequest($app['request']);

    return $voter;
});

$app['knp_menu.matcher.configure'] = $app->protect(function (Matcher $matcher) use ($app) {
    $matcher->addVoter($app['tribo.voter']);
});

$app['knp_menu.menus'] = ['main' => 'tribo_menu'];