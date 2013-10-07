<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use helpers\utils;

class video implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers
        ->get( "/full/{slug}", array( $this, 'comTemplate' ) )
        ->bind( 'video_comTemplate' );

        $controllers
        ->get( "/{slug}", array( $this, 'index' ) )
        ->bind( 'video' );

        return $controllers;
    }

    public function index( Application $app, $slug )
    {
        $app['title'] = "{$app['title']}";
        return $app['twig']->render( 'video/index.html.twig', ['video_file' => $slug] );
    }

    public function comTemplate( Application $app, $slug )
    {
        $app['title'] = "{$app['title']}";
        return $app['twig']->render( 'video/com-template.html.twig', ['video_file' => $slug] );
    }
}