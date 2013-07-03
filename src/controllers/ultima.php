<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;

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
        return $app['twig']->render( 'ultima/index.html.twig', array() );
    }

    public function show( Application $app, $slug )
    {
        return $app['twig']->render( 'ultima/show.html.twig', array('slug'=>$slug) );
    }
}
