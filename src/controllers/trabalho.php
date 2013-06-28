<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;

class trabalho implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers
        ->get( "/", array( $this, 'index' ) )
        ->bind( 'trabalho' );

        $controllers
        ->get( "/{slug}", array( $this, 'show') )
        ->bind( 'trabalho_show' );

        return $controllers;
    }

    public function index( Application $app )
    {
        return $app['twig']->render( 'trabalho/index.html.twig', array() );
    }

    public function show( Application $app, $slug )
    {
        return $app['twig']->render( 'trabalho/show.html.twig', array('slug'=>$slug) );
    }
}
