<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use helpers\utils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class busca implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers
        ->post( "/", array( $this, 'index' ) )
        ->bind( 'busca' );

        $controllers
        ->get( "/pagina/{page}", array( $this, 'page' ) )
        ->bind( 'busca_pagina' );

        return $controllers;
    }

    public function index( Application $app )
    {

        $app['palavra'] =$_POST['q'];

        $app['title'] = "{$app['translator']->trans('titulo_buscas')} - {$app['title']}";
        $items = utils::cache($app['busca.lista'], ['q'=>$app['palavra'], 'page'=>1, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, 'busca_first');
        return $app['twig']->render( 'busca/index.html.twig', [ 'pagina'=>$items['pagina'], 'paginas'=>$items['paginas'], 'items'=>$items['data'] ] );
    }

    public function page( Application $app, $page )
    {
        //$app['palavra'] =$_POST['q'];

        $items = utils::cache($app['buscas.lista'], ['q'=>$app['palavra'], 'page'=>$page, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, "buscas_{$page}");
        sleep(2);
        $buscas = "";
        foreach ($items['data'] as $item)
            $buscas .= $app['twig']->render( 'busca/partial/box-lista.html.twig', [ 'item'=>$item ] );

        return $buscas;
    }

}
