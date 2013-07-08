<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;

class fazemos implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers
        ->get( "/", array( $this, 'index' ) )
        ->bind( 'fazemos' );

        return $controllers;
    }

    public function index( Application $app )
    {

        $dados = file_get_contents(__DIR__ . '/../tmp/cases.json');
        $dados = json_decode($dados, true);
        return $app['twig']->render( 'fazemos/index.html.twig', array('cases'=>$dados) );
    }
}