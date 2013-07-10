<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use helpers\utils;

class home implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers
        ->get( "/", [$this, 'index'])
        ->bind( 'homepage' );

        $controllers
        ->post( "/pagina", array( $this, 'page' ) )
        ->bind( 'home_pagina' );

        $controllers
        ->get( "/twitter", [$this, 'twitter'] )
        ->bind( 'twitter' );

        $controllers
        ->get( "/lang/{lang}", [$this, 'lang'] )
        ->bind( 'lang' );

        return $controllers;
    }

    public function index( Application $app )
    {
        // Banner
        $banners = utils::cache('http://www.tribointeractive.com.br:81/tribosite/Home/ListarBanners', ['idioma'=>$app['translator']->getLocale()], $app, 'banner_home');

        // Boxes
        $boxes = utils::cache('http://www.tribointeractive.com.br:81/tribosite/Home/ListarDestaques', ['page'=>1, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, "boxes_home");

        // Tweets
        $tweets = [];
        // if ($app['cache']->contains('tweets'))
        // {
        //     $tweets = $app['cache']->fetch('tweets');
        // }
        // else
        // {
        //     $tweets = static::twitter($app);
        //     $app['cache']->save('tweets', $tweets, '600');
        // }

        return $app['twig']->render( 'home/index.html.twig', ['banners'=>$banners['data'], 'tweets'=>$tweets, 'boxes'=>$boxes['data'], 'pagina'=>$boxes['pagina'], 'paginas'=>$boxes['paginas'] ] );
    }

    public function page( Application $app )
    {
        $page = $app['request']->get('page',1);
        $items = utils::cache('http://www.tribointeractive.com.br:81/tribosite/Home/ListarDestaques', ['page'=>$page, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, "boxes_home_{$page}");
        $html = $app['twig']->render( 'home/partial/box-lista.html.twig', [ 'boxes'=>$items['data'] ] );
        $response = ["success"=>true, "html"=>$html, 'pagina'=>$items['pagina'], 'paginas'=>$items['paginas']];
        return $app->json($response, 201);
    }

    public function lang( Application $app, $lang)
    {
        if (file_exists(__DIR__ . "/../locales/$lang.yml"))
            $app['session']->set('current_language', $lang);

        return $app->redirect($_SERVER['HTTP_REFERER']);
    }

    static private function twitter(Application $app)
    {
        // $connection = static::getConnectionWithAccessToken($app);
        // $content = $connection->get("statuses/home_timeline");
        // var_dump($content);
        // die;

        $out = exec("curl --get 'https://api.twitter.com/1.1/statuses/user_timeline.json' --data 'contributor_details=false&count=2&exclude_replies=true&screen_name=tribo' --header 'Authorization: OAuth oauth_consumer_key=\"42T8fw5GWeqLesWQ3wNksA\", oauth_nonce=\"9ef0b7e07ac125cf87c56eea6b0b20b4\", oauth_signature=\"AckK7tMnlZ%2BsUBnr5thpLIoTpMU%3D\", oauth_signature_method=\"HMAC-SHA1\", oauth_timestamp=\"" . date_timestamp_get(date_create()) . "\", oauth_token=\"44358342-y48yiWS6Qf8t0CS0cylJrc25Jot03rxwctSJ0u0ax\", oauth_version=\"1.0\"'");
        $tweets = json_decode($out, true);
        if(isset($tweets['errors']))
            return false;
        else
        {
            $parseTweets = [];
            foreach ($tweets as $k => $tweet)
            {
                $parseTweets[$k]['text'] = $tweet['text'];
                $parseTweets[$k]['date'] = date("d/m/Y", strtotime($tweet['created_at']));
            }
            return $parseTweets;
        }
    }

    static private function Linkify($text)
    {

        $text = preg_replace_callback(
            '/(https?:\/\/\S+)/gi',
            function ($s) {
                return '<a target="_blank" href="' + $s[0] + '">' + $s[0] + '</a>';
            },
            $text
        );

        $text = preg_replace_callback(
            '/(^|)@(\w+)/gi',
            function ($s) {
                return '<a target="_blank" href="http://twitter.com/' + $s[0] + '">' + $s[0] + '</a>';
            },
            $text
        );

        $text = preg_replace_callback(
            '/(^|)#(\w+)/gi',
            function ($s) {
                return '<a target="_blank" href="http://search.twitter.com/search?q=' + str_replace($s[0],'#','%23') + '">' + $s + '</a>';
            },
            $text
        );

        return $text;
    }
}