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
        $app['title'] = "{$app['translator']->trans('titulo_a_tribo')} - {$app['title']}";
        return $app['twig']->render( 'tribo/index.html.twig', [ "locale"=>$app['translator']->getLocale() ] );
    }
}
