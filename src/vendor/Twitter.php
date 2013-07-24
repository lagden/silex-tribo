<?php
namespace vendor;

class Twitter
{
    const oauth_access_token        = '44358342-y48yiWS6Qf8t0CS0cylJrc25Jot03rxwctSJ0u0ax';
    const oauth_access_token_secret = 'fCqBSzFLolldtPrzVpuBNWfDzGUpUJEGXC6mIQEnak';
    const consumer_key              = '42T8fw5GWeqLesWQ3wNksA';
    const consumer_secret           = 'x05yNCG61V6MeycrZ5GVFAmkFkj28V99DuCrIvcjl8';

    static public function search($q, $count=4, $result_type='mixed')
    {
        $querystring = [
            'q' => urlencode($q),
            'result_type' => $result_type,
            'count' => $count,
        ];
        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        return static::request($url, $querystring);
    }

    static private function request($url, $querystring)
    {
        $baseOauth = [
            'oauth_consumer_key'     => self::consumer_key,
            'oauth_nonce'            => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token'            => self::oauth_access_token,
            'oauth_timestamp'        => time(),
            'oauth_version'          => '1.0'
        ];

        $oauth                    = array_merge($baseOauth, $querystring);
        $base_info                = static::buildBaseString($url, 'GET', $oauth);
        $composite_key            = rawurlencode(self::consumer_secret) . '&' . rawurlencode(self::oauth_access_token_secret);
        $oauth_signature          = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
        $oauth['oauth_signature'] = $oauth_signature;

        $header = [ static::buildAuthorizationHeader($oauth), 'Expect:' ];

        $options = [ 
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_HEADER         => false,
            CURLOPT_URL            => $url . '?' . http_build_query($querystring),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ];

        $c = curl_init();
        curl_setopt_array($c, $options);
        $r = curl_exec($c);
        curl_close($c);

        return $r;
    }

    static private function buildBaseString($baseURI, $method, $params)
    {
        $r = array();
        ksort($params);
        foreach($params as $key=>$value){
            $r[] = "$key=" . rawurlencode($value);
        }
        return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
    }

    static private function buildAuthorizationHeader($oauth)
    {
        $r = 'Authorization: OAuth ';
        $values = array();
        foreach($oauth as $key=>$value)
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        $r .= implode(', ', $values);
        return $r;
    }
}