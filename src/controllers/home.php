<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use helpers\utils;
use vendor\Twitter;

class home implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers
        ->get( "/", [$this, 'index'])
        ->bind( 'homepage' );

        $controllers
        ->get( "/pagina/{page}", array( $this, 'page' ) )
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
            $tweets = static::parse(Twitter::search('tribo interactive', 2, 'recent'));
            $app['cache']->save('tweets', $tweets, '600');
        }

        return $app['twig']->render( 'home/index.html.twig', ['banners'=>$banners['data'], 'tweets'=>$tweets, 'boxes'=>$boxes['data'], 'pagina'=>$boxes['pagina'], 'paginas'=>$boxes['paginas'] ] );
    }

    public function page( Application $app, $page )
    {
        $items = utils::cache($app['home.lista'], ['page'=>$page, 'pagesize'=>$app['pagesize'], 'idioma'=>$app['translator']->getLocale()], $app, "home_{$page}");
        
        return $app['twig']->render( 'home/partial/box-lista.html.twig', [ 'boxes'=>$items['data'] ] );
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

    static private function parse($r)
    {
        $data = json_decode($r);

        $tweets = [];
        foreach($data->statuses as $k => $status){
            $text = "@{$status->user->screen_name}: {$status->text}";
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
}