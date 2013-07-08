<?php
use Silex\Application;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Silex\KnpMenuServiceProvider;
use Knp\Menu\Silex\Voter\RouteVoter;

$app->register(new KnpMenuServiceProvider(), [
    'knp_menu.default_renderer' => 'twig'
]);

// var_dump(__DIR__.'/views/includes/menu.html.twig');

$app['tribo_menu'] = function($app) {
    $menu = $app['knp_menu.factory']->createItem('root', ['childrenAttributes' => ['class' => 'nav nav--banner nav--fit tribo_menu flush--bottom pad-t-20 pad-b-20']]);
    $menu->addChild('titulo_a_tribo', ['label'=>'A Tribo', 'route' => 'tribo'])->setExtra('translation', 'titulo_a_tribo');

    $menu->addChild('titulo_o_que_fazemos', ['label'=>'O que fazemos', 'route' => 'fazemos'])->setExtra('translation', 'titulo_o_que_fazemos');

    if( $app['request']->get('_route') === 'trabalho_show' && $app['request']->get('slug') )
        $menu->addChild('titulo_trabalhos', ['label'=>'Trabalhos', 'route' => "{$app['request']->get('_route')}", 'routeParameters' => ['slug'=>$app['request']->get('slug')]]);
    else
        $menu->addChild('titulo_trabalhos', ['label'=>'Trabalhos', 'route' => 'trabalho']);

    $menu['titulo_trabalhos']->setExtra('translation', 'titulo_trabalhos');

    $menu->addChild('titulo_contato', ['label'=>'Contato', 'route' => 'contato'])->setExtra('translation', 'titulo_contato');;

    if( $app['request']->get('_route') === 'ultima_show' && $app['request']->get('slug') )
        $menu->addChild('titulo_ultimas', ['label'=>'Últimas', 'route' => "{$app['request']->get('_route')}", 'routeParameters' => ['slug'=>$app['request']->get('slug')]]);
    else
        $menu->addChild('titulo_ultimas', ['label'=>'Últimas', 'route' => 'ultima']);

    $menu['titulo_ultimas']->setExtra('translation', 'titulo_ultimas');

    return $menu;
};

$app['tribo_lingua'] = function($app) {
    $menu = $app['knp_menu.factory']->createItem('root', ['childrenAttributes' => ['class' => 'nav flush--bottom']]);
    $menu->addChild('en-US', ['label'=>'English', 'route' => 'lang', 'routeParameters' => ['lang'=>'en-US']]);
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
