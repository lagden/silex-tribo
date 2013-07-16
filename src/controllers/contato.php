<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use helpers\utils;

class contato implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers
        ->get( "/", array( $this, 'index' ) )
        ->bind( 'contato' );

        return $controllers;
    }

    public function index( Application $app )
    {
        $app['title'] = "{$app['translator']->trans('titulo_contato')} - {$app['title']}";
        return $app['twig']->render( 'contato/index.html.twig', array() );
    }
}
