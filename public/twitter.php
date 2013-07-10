<?php
$url                       = "https://api.twitter.com/1.1/statuses/user_timeline.json";

$oauth_access_token        = "44358342-y48yiWS6Qf8t0CS0cylJrc25Jot03rxwctSJ0u0ax";
$oauth_access_token_secret = "fCqBSzFLolldtPrzVpuBNWfDzGUpUJEGXC6mIQEnak";
$consumer_key              = "42T8fw5GWeqLesWQ3wNksA";
$consumer_secret           = "x05yNCG61V6MeycrZ5GVFAmkFkj28V99DuCrIvcjl8";

$usuario                   = "tribo";
$quantidade                = 2; //qtde de twits pra carregar

function buildBaseString($baseURI, $method, $params) {
    $r = array();
    ksort($params);
    foreach($params as $key=>$value){
        $r[] = "$key=" . rawurlencode($value);
    }
    return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
}

function buildAuthorizationHeader($oauth) {
    $r = 'Authorization: OAuth ';
    $values = array();
    foreach($oauth as $key=>$value)
        $values[] = "$key=\"" . rawurlencode($value) . "\"";
    $r .= implode(', ', $values);
    return $r;
}


$oauth = array( 
    'count'                  => $quantidade,
    'screen_name'            => $usuario,
    'oauth_consumer_key'     => $consumer_key,
    'oauth_nonce'            => time(),
    'oauth_signature_method' => 'HMAC-SHA1',
    'oauth_token'            => $oauth_access_token,
    'oauth_timestamp'        => time(),
    'oauth_version'          => '1.0'
);


$base_info                = buildBaseString($url, 'GET', $oauth);
$composite_key            = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
$oauth_signature          = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
$oauth['oauth_signature'] = $oauth_signature;

// Make Requests
$header = array(buildAuthorizationHeader($oauth), 'Expect:');
// $url = "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=pacaembuconstru&count=4&oauth_consumer_key=VRBmqisQEbwAwSfv9OV2Kg&oauth_nonce=1365964960&oauth_signature_method=HMAC-SHA1&oauth_token=39752284-AYiGnio9ziiFh0X7aBSollXQVOthn5seeyea5MN9g&oauth_timestamp=" . time() . "&oauth_version=1.0&oauth_signature=EwexWgaZgZBilNWPkl7%2FtRYsIAE%3D";

$options = array( 
    CURLOPT_HTTPHEADER     => $header,
    CURLOPT_HEADER         => false,
    CURLOPT_URL            => $url . "?count=$quantidade&screen_name=$usuario",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false
);

$feed         = curl_init();
curl_setopt_array($feed, $options);
$json         = curl_exec($feed);
curl_close($feed);

$twitter_data = json_decode($json);
// var_dump($twitter_data);

// se quiser, pode parar aqui, embaixo ele faz um LI pra cada twit, convertendo usuario e link para a[href]

foreach($twitter_data as $status){
    $text = $status->text;
    $data = $status->created_at;

    $text = '@' . $status->user->screen_name . ' ' . $text;

    $text = preg_replace('@(https?://([-\w\.]+)+(/([\w/_\.]*(\?\S+)?(#\S+)?)?)?)@', '<a href="$1">$1</a>', $text);
    $text = preg_replace('/@(\w+)/', '<a href="http://twitter.com/$1">@$1</a>', $text);
    $text = preg_replace('/\s#(\w+)/', ' <a href="http://search.twitter.com/search?q=%23$1">#$1</a>', $text);

    setlocale (LC_ALL, 'pt_BR','ptb');
    $the_data = new DateTime($data);
    $created_at = utf8_encode(strftime('%d %B', $the_data->format('U')));
    echo "<li>$text<br><span>$created_at</span><li/>";
}