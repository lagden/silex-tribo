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
        ->post( "/pagina", array( $this, 'page' ) )
        ->bind( 'trabalho_pagina' );

        $controllers
        ->get( "/{slug}", array( $this, 'show') )
        ->bind( 'trabalho_show' );

        return $controllers;
    }

    public function index( Application $app )
    {
        $boxes = utils::cache('http://www.tribointeractive.com.br:81/tribosite/Trabalhos/Listar', ['page'=>1, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, 'trabalho_first');
        return $app['twig']->render( 'trabalho/index.html.twig', ['boxes'=>$boxes['data'], 'pagina'=>$boxes['pagina'], 'paginas'=>$boxes['paginas'] ] );
    }

    public function page( Application $app )
    {
        $page = $request->get('page', 1);
        $items = utils::cache('http://www.tribointeractive.com.br:81/tribosite/Trabalhos/Listar', ['page'=>$page, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, "ultimas_{$page}");
        $html = $app['twig']->render( 'home/partial/box-lista.html.twig', [ 'boxes'=>$items['data'] ] );
        $response = ["success"=>true, "html"=>$html, 'pagina'=>$items['pagina'], 'paginas'=>$items['paginas']];
        return $app->json($response, 201);
    }

    public function show( Application $app, $slug )
    {
        $item = utils::cache("http://www.tribointeractive.com.br:81/tribosite/Trabalhos/Detalhe", ['slug'=>$slug, 'idioma'=>$app['translator']->getLocale()], $app, 'trabalhos_show');
        if(isset($item['data'][0]))
        {
            $item = $item['data'][0];
            $app['title'] = "{$item['nome']} - {$app['title']}";
        }
        return $app['twig']->render( 'trabalho/show.html.twig', [ 'item'=>$item ] );
    }
}