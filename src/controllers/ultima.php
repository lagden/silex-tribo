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
        ->get( "/{slug}", array( $this, 'show') )
        ->bind( 'ultima_show' );

        return $controllers;
    }

    public function index( Application $app )
    {
        $items = utils::cache('http://www.tribointeractive.com.br:81/tribosite/Noticias/Listar', ['idioma'=>$app['translator']->getLocale()], $app, 'ultimas');
        return $app['twig']->render( 'ultima/index.html.twig', [ 'items'=>$items ] );
    }

    public function show( Application $app, $slug )
    {
        $item = utils::cache("http://www.tribointeractive.com.br:81/tribosite/Noticias/Detalhe", ['slug'=>$slug, 'idioma'=>$app['translator']->getLocale()], $app, 'ultimas_show');
        $item = isset($item[0]) ? $item[0] : false;
        return $app['twig']->render( 'ultima/show.html.twig', [ 'item'=>$item ] );
    }
}
