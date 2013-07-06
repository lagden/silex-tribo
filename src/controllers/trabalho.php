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
        //var_dump($app['request']);die;

        $dadosFake = [
            'titulo'=>'Possante Virtual',
            'cliente'=>'Connect Parts',
            'imagem_destaque'=>'1180x490xC.jpg',
            'sobre_o_projeto'=>'<p>Lorem ipsum Mollit officia sit dolor proident labore do tempor ullamco cupidatat velit commodo mollit in dolore sint non aliqua consectetur occaecat deserunt Excepteur sit magna velit enim eu do nostrud Ut non qui est commodo consectetur nulla.</p>',
            'url_projeto'=>'http://felquis.com/',
            'label_url_projeto'=>'felquis.com',
            'o_que_fizemos'=>'<ul><li>Fizemos isso</li><li>Fizemos também</li><li>Fizemos aquilo outro</li></ul>',
            'imagens'=>[
                ['imagem'=>'380x235xC.jpg', 'alt'=>'Primeira Imagem'],
                ['imagem'=>'380x235.jpg', 'alt'=>'Segunda Imagem'],
                ['imagem'=>'380x235xC.jpg', 'alt'=>'Terceira Imagem'],
                ['imagem'=>'380x235.jpg', 'alt'=>'Quarta Imagem'],
            ],
            'ficha_tecnica'=>'<strong>Direcao De Criacao: </strong>Roger Rocha <br> <strong>Criação:</strong> Ricardo Schreier, Plinio Nitzche, Rodrigo Bondioli, Kelvin Yamashita <br><strong>Planejamento: </strong>Monica Muller<br><strong>UX: </strong> Anna Telles, Michele Valongo<br><strong>Atendimento: </strong>Sabrina Giorgi'
        ];

        return $app['twig']->render( 'trabalho/show.html.twig', array('slug'=>$slug, 'trabalho'=>$dadosFake) );
    }
}