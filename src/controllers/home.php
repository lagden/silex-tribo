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
        ->get( "/empty-cache", [$this, 'emptyCache'] )
        ->bind( 'empty-cache' );

        $controllers
        ->get( "/lang/{lang}", [$this, 'lang'] )
        ->bind( 'lang' );

        return $controllers;
    }

    public function index( Application $app )
    {
        // Banner
        $banners = utils::cache($app['home.banner'], ['idioma'=>$app['translator']->getLocale()], $app, 'banner_home');
        if(!isset($banners['data'])) $banners = ['data'=>null, 'pagina'=>null, 'paginas'=>null ];

        // Boxes
        $boxes = utils::cache($app['home.lista'], ['page'=>1, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, "boxes_home");
        if(!isset($boxes['data'])) $boxes = ['data'=>null, 'pagina'=>null, 'paginas'=>null ];

        // Tweets
        $tweets = [];
        if ($app['cache']->contains('tweets'))
        {
            $tweets = $app['cache']->fetch('tweets');
        }
        else
        {
            $tweets = static::twitterGF($app);
            $app['cache']->save('tweets', $tweets, '600');
        }

        return $app['twig']->render( 'home/index.html.twig', ['banners'=>$banners['data'], 'tweets'=>$tweets, 'boxes'=>$boxes['data'], 'pagina'=>$boxes['pagina'], 'paginas'=>$boxes['paginas'] ] );
    }

    public function page( Application $app )
    {
        $page = $app['request']->get('page', 1);
        $items = utils::cache($app['home.lista'], ['page'=>$page, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, "boxes_home_{$page}");
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

    public function emptyCache( Application $app )
    {
        return $app->json([$app['cache']->deleteAll()], 201);
    }

    static private function twitterGF(Application $app)
    {
        $url                       = "https://api.twitter.com/1.1/statuses/user_timeline.json";

        $oauth_access_token        = $app['twitter.access_token'];
        $oauth_access_token_secret = $app['twitter.access_token_secret'];
        $consumer_key              = $app['twitter.key'];
        $consumer_secret           = $app['twitter.secret'];

        $usuario                   = "tribo";
        $quantidade                = 2;

        $oauth = [
            'count'                  => $quantidade,
            'screen_name'            => $usuario,
            'oauth_consumer_key'     => $consumer_key,
            'oauth_nonce'            => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token'            => $oauth_access_token,
            'oauth_timestamp'        => time(),
            'oauth_version'          => '1.0'
        ];


        $base_info                = static::buildBaseString($url, 'GET', $oauth);
        $composite_key            = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
        $oauth_signature          = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
        $oauth['oauth_signature'] = $oauth_signature;

        // Make Requests
        $header = [static::buildAuthorizationHeader($oauth), 'Expect:'];

        $options = [ 
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_HEADER         => false,
            CURLOPT_URL            => $url . "?count=$quantidade&screen_name=$usuario",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ];

        $feed         = curl_init();
        curl_setopt_array($feed, $options);
        $json         = curl_exec($feed);
        curl_close($feed);

        $twitter_data = json_decode($json);

        $tweets = [];
        foreach($twitter_data as $k => $status){
            $text = $status->text;
            $data = $status->created_at;

            $text = preg_replace('@(https?://([-\w\.]+)+(/([\w/_\.]*(\?\S+)?(#\S+)?)?)?)@', '<a class="gray" href="$1">$1</a>', $text);
            $text = preg_replace('/@(\w+)/', '<a class="gray" href="http://twitter.com/$1">@$1</a>', $text);
            $text = preg_replace('/#(\w+)/', ' <a class="gray" href="http://search.twitter.com/search?q=%23$1">#$1</a>', $text);

            setlocale (LC_ALL, 'pt_BR','ptb');
            $the_data = new \DateTime($data);
            $created_at = utf8_encode(strftime('%d/%m', $the_data->format('U')));

            $tweets[$k]['text'] = $text;
            $tweets[$k]['created_at'] = $created_at;
        }

        return $tweets;
    }

    static private function buildBaseString($baseURI, $method, $params) {
        $r = array();
        ksort($params);
        foreach($params as $key=>$value){
            $r[] = "$key=" . rawurlencode($value);
        }
        return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
    }

    static private function buildAuthorizationHeader($oauth) {
        $r = 'Authorization: OAuth ';
        $values = array();
        foreach($oauth as $key=>$value)
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        $r .= implode(', ', $values);
        return $r;
    }
}