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
        ->get( "/lang/{lang}", [$this, 'lang'] )
        ->bind( 'lang' );

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

    public function lang( Application $app, $lang)
    {
        if (file_exists(__DIR__ . "/../locales/$lang.yml"))
            $app['session']->set('current_language', $lang);

        return $app->redirect($_SERVER['HTTP_REFERER']);
    }
}
