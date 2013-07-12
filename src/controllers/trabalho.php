<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use helpers\utils;

class trabalho implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers
        ->get( "/", array( $this, 'index' ) )
        ->bind( 'trabalho' );

        $controllers
        ->get( "/pagina/{page}", array( $this, 'page' ) )
        ->bind( 'trabalho_pagina' );

        $controllers
        ->get( "/{slug}", array( $this, 'show') )
        ->bind( 'trabalho_show' );

        return $controllers;
    }

    public function index( Application $app )
    {
        $app['title'] = "{$app['translator']->trans('titulo_trabalhos')} - {$app['title']}";
        $boxes = utils::cache($app['trabalho.lista'], ['page'=>1, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, 'trabalhos_first');
        return $app['twig']->render( 'trabalho/index.html.twig', ['boxes'=>$boxes['data'], 'pagina'=>$boxes['pagina'], 'paginas'=>$boxes['paginas'] ] );
    }

    public function page( Application $app, $page )
    {
        $items = utils::cache($app['trabalho.lista'], ['page'=>$page, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, "trabalhos_{$page}");
        sleep(2);
        return $app['twig']->render( 'trabalho/partial/box-lista.html.twig', [ 'boxes'=>$items['data'] ] );
    }

    public function show( Application $app, $slug )
    {
        $item = utils::cache($app['trabalho.detalhe'], ['slug'=>$slug, 'idioma'=>$app['translator']->getLocale()], $app, "trabalhos_show_{$slug}");
        if(isset($item['data'][0]))
        {
            $item = $item['data'][0];
            $app['title'] = "{$item['nome']} - {$app['title']}";
        }
        return $app['twig']->render( 'trabalho/show.html.twig', [ 'item'=>$item ] );
    }
}