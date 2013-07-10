<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use helpers\utils;

class ultima implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers
        ->get( "/", array( $this, 'index' ) )
        ->bind( 'ultima' );

        $controllers
        ->post( "/pagina", array( $this, 'page' ) )
        ->bind( 'ultima_pagina' );

        $controllers
        ->get( "/{slug}", array( $this, 'show') )
        ->bind( 'ultima_show' );

        return $controllers;
    }

    public function index( Application $app )
    {
        $app['title'] = "{$app['translator']->trans('titulo_ultimas')} - {$app['title']}";
        $destaques = utils::cache($app['ultimas.destaque'], ['idioma'=>$app['translator']->getLocale()], $app, 'ultimas_destaques');
        $items = utils::cache($app['ultimas.lista'], ['page'=>1, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, 'ultimas_first');
        return $app['twig']->render( 'ultima/index.html.twig', [ 'pagina'=>$items['pagina'], 'paginas'=>$items['paginas'], 'items'=>$items['data'], 'destaques'=>$destaques['data'] ] );
    }

    public function page( Application $app )
    {
        $page = $app['request']->get('page', 1);
        $items = utils::cache($app['ultimas.lista'], ['page'=>$page, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, "ultimas_{$page}");
        $ultimas = "";
        foreach ($items['data'] as $item)
            $ultimas .= $app['twig']->render( 'ultima/partial/box-lista.html.twig', [ 'item'=>$item ] );
        $response = ["success"=>true, "html"=>$ultimas, 'pagina'=>$items['pagina'], 'paginas'=>$items['paginas']];
        return $app->json($response, 201);
    }

    public function show( Application $app, $slug )
    {
        $item = utils::cache("http://www.tribointeractive.com.br:81/tribosite/Noticias/Detalhe", ['slug'=>$slug, 'idioma'=>$app['translator']->getLocale()], $app, 'ultimas_show');
        if(isset($item[0]))
        {
            $item = $item[0];
            $app['title'] = "{$item['titulo']} - {$app['title']}";
        }

        return $app['twig']->render( 'ultima/show.html.twig', [ 'item'=>$item ] );
    }
}
