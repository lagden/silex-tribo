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
        ->get( "/", [$this, 'index'])
        ->bind( 'homepage' );

        $controllers
        ->get( "/mais-box/{number}", [$this, 'mais'] )
        ->bind( 'mais' );

        return $controllers;
    }

    public function index( Application $app )
    {
        return $app['twig']->render( 'home/index.html.twig', [] );
    }

    public function mais( Application $app, $number )
    {
        $boxes = $app['twig']->render( 'home/partial/box.html.twig', ['number'=>$number] );
        $response = ["success"=>true, "html"=>$boxes];
        return $app->json($response, 201);
    }
}
