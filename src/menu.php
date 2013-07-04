<?php
use Silex\Application;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Silex\KnpMenuServiceProvider;
use Knp\Menu\Silex\Voter\RouteVoter;

$app->register(new KnpMenuServiceProvider());
$app['tribo_menu'] = function($app) {
    $menu = $app['knp_menu.factory']->createItem('root', ['childrenAttributes' => ['class' => 'nav nav--banner nav--fit tribo_menu flush--bottom pad-t-20 pad-b-20']]);
    $menu->addChild('A Tribo', ['route' => 'tribo']);
    $menu->addChild('O que fazemos', ['route' => 'fazemos']);
    $menu->addChild('Trabalhos', ['route' => 'trabalho']);
    $menu->addChild('Contato', ['route' => 'contato']);
    $menu->addChild('Últimas', ['route' => 'ultima']);
    return $menu;
};

$app['tribo_lingua'] = function($app) {
    $menu = $app['knp_menu.factory']->createItem('root', ['childrenAttributes' => ['class' => 'nav flush--bottom']]);
    $menu->addChild('en', ['label'=>'English', 'route' => 'lang', 'routeParameters' => ['lang'=>'en']]);
    $menu->addChild('pt-BR', ['label'=>'Português', 'route' => 'lang', 'routeParameters' => ['lang'=>'pt-BR']]);
    $menu["{$app['translator']->getLocale()}"]->setAttribute('class', 'current');
    return $menu;
};

$app['tribo_social'] = function($app) {
    $menu = $app['knp_menu.factory']->createItem('root', ['childrenAttributes' => ['class' => 'nav flush--bottom']]);

    $menu->addChild('Facebook', [ 'uri' => 'https://www.facebook.com/TriboInteractive' ] );
    $menu['Facebook']->setLinkAttribute('class', 'ir geral-facebook');

    $menu->addChild('Twitter', [ 'uri' => 'https://twitter.com/tribo' ]);
    $menu['Twitter']->setLinkAttribute('class', 'ir geral-twitter');
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

$app['knp_menu.menus'] = [
    'main' => 'tribo_menu',
    'lingua' => 'tribo_lingua',
    'social' => 'tribo_social',
];