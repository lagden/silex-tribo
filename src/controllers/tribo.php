<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;

class tribo implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers
        ->get( "/", array( $this, 'index' ) )
        ->bind( 'tribo' );

        return $controllers;
    }

    public function index( Application $app )
    {
        return $app['twig']->render( 'tribo/index.html.twig', array() );
    }
}
