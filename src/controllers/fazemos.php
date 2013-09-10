<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use helpers\utils;

class fazemos implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers
        ->get( "/", array( $this, 'index' ) )
        ->bind( 'fazemos' );

        $controllers
        ->post( "/cases", array( $this, 'categoria' ) )
        ->bind( 'cases' );

        return $controllers;
    }

    public function index( Application $app )
    {
        $app['title'] = "{$app['translator']->trans('titulo_o_que_fazemos')} - {$app['title']}";
        $cases = utils::cache($app['trabalho.cases'], ['idioma'=>$app['translator']->getLocale()], $app, "cases");
        // return $app['twig']->render( 'fazemos/index.html.twig', ['cases'=>$cases] );
        return $app['twig']->render( 'fazemos/index.html.twig' );
    }

    public function categoria( Application $app )
    {
        // var_dump($app['request']->request->all());die;
        $categoria = $app['request']->get('categoria');
        $cases = utils::cache($app['trabalho.cases.categoria'], ['categoria'=>$categoria, 'idioma'=>$app['translator']->getLocale()], $app, "cases_categoria");
        return $app['twig']->render( 'fazemos/cases.html.twig', ['cases'=>$cases] );
        // return $app->json($cases, 201);
    }
}