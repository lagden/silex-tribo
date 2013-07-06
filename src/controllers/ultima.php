<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;

class ultima implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers
        ->get( "/", array( $this, 'index' ) )
        ->bind( 'ultima' );

        $controllers
        ->get( "/{slug}", array( $this, 'show') )
        ->bind( 'ultima_show' );

        return $controllers;
    }

    public function index( Application $app )
    {
        // Lista
        $items = [];
        $out = exec("curl --get 'http://fenix:81/tribosite/Noticias/Listar' --data 'idioma={$app['translator']->getLocale()}'");
        $out = json_decode($out, true);
        if($out['success'])
        {
            foreach ($out['data'] as $k => $item) {
                $items[$k] = $item;
            }
        }

        return $app['twig']->render( 'ultima/index.html.twig', [ 'items'=>$items ] );
    }

    public function show( Application $app, $slug )
    {
        $galeria = [
            ['thumb'=>'130x80.jpg', 'image'=>"580x360.jpg"],
            ['thumb'=>'130x80xC.jpg', 'image'=>"580x360xC.jpg"],
            ['thumb'=>'130x80.jpg', 'image'=>"580x360.jpg"],
            ['thumb'=>'130x80xC.jpg', 'image'=>"580x360xC.jpg"],
        ];

        $dado = [
            "titulo"=>"Apenas um show",
            "conteudo"=>"<p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.</p><p>Suco de cevadiss, é um leite divinis, qui tem lupuliz, matis, aguis e fermentis. Interagi no mé, cursus quis, vehicula ac nisi. Aenean vel dui dui. Nullam leo erat, aliquet quis tempus a, posuere ut mi. Ut scelerisque neque et turpis posuere pulvinar pellentesque nibh ullamcorper. Pharetra in mattis molestie, volutpat elementum justo. Aenean ut ante turpis. Pellentesque laoreet mé vel lectus scelerisque interdum cursus velit auctor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ac mauris lectus, non scelerisque augue. Aenean justo massa.</p>",
        ];
        return $app['twig']->render( 'ultima/show.html.twig', array('dado'=>$dado, 'galeria'=>$galeria) );
    }
}
