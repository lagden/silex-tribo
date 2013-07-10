<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use helpers\utils;

class trabalho implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers
        ->get( "/", array( $this, 'index' ) )
        ->bind( 'trabalho' );

        $controllers
        ->post( "/pagina", array( $this, 'page' ) )
        ->bind( 'trabalho_pagina' );

        $controllers
        ->get( "/{slug}", array( $this, 'show') )
        ->bind( 'trabalho_show' );

        return $controllers;
    }

    public function index( Application $app )
    {
        // $destaques = utils::cache('http://www.tribointeractive.com.br:81/tribosite/Noticias/ListarDestaques', ['idioma'=>$app['translator']->getLocale()], $app, 'ultimas_destaques');
        $boxes = utils::cache('http://www.tribointeractive.com.br:81/tribosite/Noticias/Listar', ['page'=>1, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, 'trabalho_first');
        return $app['twig']->render( 'trabalho/index.html.twig', ['boxes'=>$boxes['data'], 'pagina'=>$boxes['pagina'], 'paginas'=>$boxes['paginas'] ] );
    }

    public function show( Application $app, $slug )
    {
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

    public function page( Application $app )
    {
        $page = $request->get('page', 1);
        $items = utils::cache('http://www.tribointeractive.com.br:81/tribosite/Noticias/Listar', ['page'=>$page, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, "ultimas_{$page}");
        $html = $app['twig']->render( 'home/partial/box-lista.html.twig', [ 'boxes'=>$items['data'] ] );
        $response = ["success"=>true, "html"=>$html, 'pagina'=>$items['pagina'], 'paginas'=>$items['paginas']];
        return $app->json($response, 201);
    }
}