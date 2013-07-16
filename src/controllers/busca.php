<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use helpers\utils;


class busca implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers
        ->get( "/", array( $this, 'index' ) )
        ->bind( 'busca' );

        $controllers
        ->get( "/pagina/{page}/{q}", array( $this, 'page' ) )
        ->bind( 'busca_pagina' );

        return $controllers;
    }

    public function index( Application $app )
    {
        $palavra = $app['request']->get('q');

        $app['title'] = "{$palavra} - {$app['translator']->trans('titulo_busca')} - {$app['title']}";
        $items = utils::cache($app['busca.lista'], ['q'=>$palavra, 'page'=>1, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, 'busca_first');
        return $app['twig']->render( 'busca/index.html.twig', [ 'q'=>$palavra, 'pagina'=>$items['pagina'], 'paginas'=>$items['paginas'], 'items'=>$items['data'] ] );
    }

    public function page( Application $app, $page, $q )
    {

        $items = utils::cache($app['busca.lista'], ['q'=>$q, 'page'=>$page, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, "buscas_{$page}");
        
        $buscas = "";
        foreach ($items['data'] as $item)
            $buscas .= $app['twig']->render( 'busca/partial/box-lista.html.twig', [ 'item'=>$item ] );

        return $buscas;
    }
}
