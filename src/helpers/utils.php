<?php
namespace helpers;

use Silex\Application;

class utils
{
    static public function cache($url, $data, Application $app, $key = null)
    {
        if($key)
        {
            $key = mt_rand()."{$key}_{$app['translator']->getLocale()}";
            // $key = "{$key}_{$app['translator']->getLocale()}";

            if ($app['cache']->contains($key))
            {
                $items = $app['cache']->fetch($key); 
            }
            else
            {
                $items = static::curl($url, $data);
                // $app['cache']->save($key, $items, '3600');
            }
        }
        else
            $items = static::curl($url, $data);
        return $items;
    }

    static public function curl($url, $data)
    {
        $query = http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "{$url}?{$query}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch); 
        curl_close($ch);

        $out = json_decode($response, true);
        if($out['success'])
            return $out;
        else
            return [];
    }
}