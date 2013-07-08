<?php
namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use helpers\utils;

// require_once __DIR__ . '/../vendor/twitteroauth/twitteroauth.php';

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
        $banners = utils::cache('http://www.tribointeractive.com.br:81/tribosite/Home/ListarBanners', ['idioma'=>$app['translator']->getLocale()], $app, 'banner_home');

        // Boxes
        $boxes = utils::cache('http://www.tribointeractive.com.br:81/tribosite/Home/ListarDestaques', ['idioma'=>$app['translator']->getLocale()], $app, 'boxes_home');
        // var_dump($boxes);die;

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

        return $app['twig']->render( 'home/index.html.twig', ['banners'=>$banners, 'tweets'=>$tweets, 'boxes'=>$boxes] );
    }

    public function mais( Application $app, $number )
    {
        // {{ include('/includes/partials/box.html.twig', { "item": item, "css": 'box', "isFull": true }) }}
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

    static private function getConnectionWithAccessToken(Application $app) {
        $connection = new TwitterOAuth($app['twitter.key'], $app['twitter.secret'], $app['twitter.access_token'], $app['twitter.access_token_secret']);
        return $connection;
    }
 
// $connection = getConnectionWithAccessToken("abcdefg", "hijklmnop");
// $content = $connection->get("statuses/home_timeline");

    // static private function getRequestToken( Application $app ) {
    //     $urlParams = array (
    //         "oauth_consumer_key" => $app['twitter.key'],
    //         "oauth_signature_method" => "HMAC-SHA1",
    //         "oauth_timestamp" => date_timestamp_get(date_create()),
    //         "oauth_nonce" => md5 ( uniqid ( rand(), true ) ),
    //         "oauth_version" => "1.0"
    //     );

    //     ksort ( $urlParams );

    //     foreach ( $urlParams as $k => $v ) $joinedParams[] = "{$k} = {$v}";
    //     $joinedParams = implode ( "&", $joinedParams );

    //     $baseString = "GET&" . rawurlencode ( $app['twitter.request_token'] ) . "&" . rawurlencode ( $joinedParams );
    //     $secret = rawurlencode ( $app['twitter.secret'] ) . "&";
    //     $urlParams ['oauth_signature'] = rawurlencode ( static::signRequest ( $secret, $baseString ) );
    //     ksort($urlParams);

    //     // We need to build an array of headers for CURL
    //     $urlParts = parse_url (  $app['twitter.request_token'] );
    //     $header = array ('Expect:' );
    //     $oauthHeader = 'Authorization: OAuth realm="' . $urlParts ['path'] . '", ';
    //     foreach ( $urlParams as $name => $value ) {
    //         $oauthHeader .= "{$name}=\"{$value}\", ";
    //     }
    //     $header [] = substr ( $oauthHeader, 0, - 2 );

    //     // Ask Twitter for a request token
    //     $ch = curl_init ( $app['twitter.request_token'] );
    //     curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
    //     curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    //     curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
    //     curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
    //     $content = curl_exec ( $ch );
    //     curl_close ( $ch );

    //     var_dump($content);

    //     // Create the url from the curl answer
    //     parse_str($content, $output);
    //     $url = "{$app['twitter.authorize_url']}?oauth_token=" . $output["oauth_token"];
    //     echo $url ;
    // }

    // static private function signRequest($secret, $baseString) {
    //     return base64_encode ( hash_hmac ( 'sha1', $baseString, $secret, TRUE ) );
    // }
}