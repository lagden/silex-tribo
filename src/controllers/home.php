<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;

class home implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers
        ->get( "/", array( $this, 'index' ) )
        ->bind( 'homepage' );

        return $controllers;
    }

    public function index( Application $app )
    {
        return $app['twig']->render( 'home/index.html.twig', array() );
    }
}
