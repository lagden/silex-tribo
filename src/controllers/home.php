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
        ->get( "/twitter", [$this, 'twitter'] )
        ->bind( 'twitter' );

        return $controllers;
    }

    public function index( Application $app )
    {
        // Banner
        $banners = [
            ['image'=>'1180x490.jpg', 'alt'=>'Primeira Imagem', 'url'=>'http://tribointeractive.com.br'],
            ['image'=>'1180x490xC.jpg', 'alt'=>'Segunda Imagem', 'url'=>'http://felquis.com'],
            ['image'=>'1180x490.jpg', 'alt'=>'Terceira Imagem', 'url'=>'http://fb.com/DevCast'],
            ['image'=>'1180x490xC.jpg', 'alt'=>'Quarta Imagem', 'url'=>'http://twitter.com/felquis']
        ];

        // Tweets
        $tweets = static::twitter();

        return $app['twig']->render( 'home/index.html.twig', ['banners'=>$banners, 'tweets'=>$tweets] );
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

    static private function twitter()
    {
        $out = exec("curl --get 'https://api.twitter.com/1.1/statuses/user_timeline.json' --data 'contributor_details=false&count=2&exclude_replies=true&screen_name=tribo' --header 'Authorization: OAuth oauth_consumer_key=\"42T8fw5GWeqLesWQ3wNksA\", oauth_nonce=\"9ef0b7e07ac125cf87c56eea6b0b20b4\", oauth_signature=\"AckK7tMnlZ%2BsUBnr5thpLIoTpMU%3D\", oauth_signature_method=\"HMAC-SHA1\", oauth_timestamp=\"1373050111\", oauth_token=\"44358342-y48yiWS6Qf8t0CS0cylJrc25Jot03rxwctSJ0u0ax\", oauth_version=\"1.0\"'");
        $tweets = json_decode($out, true);
        $parseTweets = [];
        foreach ($tweets as $k => $tweet)
        {
            $parseTweets[$k]['text'] = $tweet['text'];
            $parseTweets[$k]['date'] = date("d/m/Y", strtotime($tweet['created_at']));
        }

        return $parseTweets;
    }
}