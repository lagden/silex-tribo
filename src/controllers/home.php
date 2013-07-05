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

        $controllers
        ->get( "/facebook-updates", [$this, 'facebook'] )
        ->bind( 'facebook' );

        return $controllers;
    }

    public function index( Application $app )
    {
        $banners = [
            ['image'=>'1180x490.jpg', 'alt'=>'Primeira Imagem', 'url'=>'http://tribointeractive.com.br'],
            ['image'=>'1180x490xC.jpg', 'alt'=>'Segunda Imagem', 'url'=>'http://felquis.com'],
            ['image'=>'1180x490.jpg', 'alt'=>'Terceira Imagem', 'url'=>'http://fb.com/DevCast'],
            ['image'=>'1180x490xC.jpg', 'alt'=>'Quarta Imagem', 'url'=>'http://twitter.com/felquis']
        ];

        return $app['twig']->render( 'home/index.html.twig', ['banners'=>$banners] );
    }

    public function mais( Application $app, $number )
    {
        $boxes = $app['twig']->render( 'home/partial/box.html.twig', ['number'=>$number] );
        $response = ["success"=>true, "html"=>$boxes];
        return $app->json($response, 201);
    }

    public function facebook( Application $app )
    {

        // ID Da página que você quer o feed
        $profile_id = "368231783220476";

        // Quantidade de posts que você quer
        $quantidade = 5;

        // ID e App Secret do app no Facebook
        $app_id = "201185700039693";
        $app_secret = "cd73e7d48fb944dcf4b46960fff71870";

        // $authToken = static::fetchUrl("https://graph.facebook.com/oauth/access_token?grant_type=client_credentials&client_id={$app_id}&client_secret={$app_secret}");

        // $json_object = json_decode(static::fetchUrl("https://graph.facebook.com/{$profile_id}/feed?{$authToken}&limit={$quantidade}"), true);

        $json_object = json_decode( file_get_contents(__DIR__ . "/../tmp/facebook.json"), true );

        $response = ["success"=>true, "data"=>$json_object];

        return $app->json($response, 201);
    }

    public function lang( Application $app, $lang)
    {
        if (file_exists(__DIR__ . "/../locales/$lang.yml"))
            $app['session']->set('current_language', $lang);

        return $app->redirect($_SERVER['HTTP_REFERER']);
    }

    static private function fetchUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        // You may need to add the line below
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);

        $feedData = curl_exec($ch);
        curl_close($ch);

        return $feedData;
    }
}