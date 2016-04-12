<?php

class Curl extends Controller
{

    private static $proxyAuth = 'tomylyjon';

    static function CurlOne($url, $proxy, $header = 0)
    {
        $curl = curl_init();

        if (!empty($proxy))
        {
            curl_setopt($curl, CURLOPT_PROXY, $proxy);
            curl_setopt($curl, CURLOPT_PROXYUSERPWD, self::$proxyAuth);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, $header);
        
        $res = curl_exec($curl);
        curl_close($curl);
        if (!$res)
            print_r("No curl result, proxy was probably locked or wrong.\n");
        return ($res);
    }

    /*
    ** Permet de curler plusieurs urls avec la meme connexion;
    */
    static function CurlMany($urls = [], $proxy, $header = 0)
    {
        $res = [];
        $curl = curl_init();
        if (!empty($proxy))
        {
            curl_setopt($curl, CURLOPT_PROXY, $proxy);
            curl_setopt($curl, CURLOPT_PROXYUSERPWD, self::$proxyAuth);
        }
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, $header);
        foreach ($urls as $url)
        {
            curl_setopt($curl, CURLOPT_URL, $url);
            array_push($res, curl_exec($curl));
        }
        curl_close($curl);
        if (!$res)
            print_r("No curl result, proxy was probably locked or wrong.\n");
        return ($res);
    }

    /*
    ** Meme chose de CurlMany mais specifique a facebook openGraph
    ** Recupere un tableau params au format :
    ** @params[url, emailACleaner]
    ** Retourne un tableau au format :
    Format de retour :
    Array
        (
            [0] => Array
            (
                [email] => email@gmail.com
                [name] => Arnaud Legeida
                [id] => 100008705166724
            )
            
            [1] => Array
            (
                [email] => kevinpiac@gmail.com
                [name] => Kevin Piacentini
                [id] => 100003820433022
            )
        )
    */
    static function CurlOpenGraph($params = [], $proxy = null, $timeout = 3, $header = 0)
    {
        $ret = [];
        $curl = curl_init();
        print_r("Getting proxy -> " . $proxy . "\n");
        if (!empty($proxy))
        {
            curl_setopt($curl, CURLOPT_PROXY, $proxy);
            curl_setopt($curl, CURLOPT_PROXYUSERPWD, self::$proxyAuth);
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, $header);
        foreach ($params as $k => $v)
        {
            curl_setopt($curl, CURLOPT_URL, $v['url']);
            $json = curl_exec($curl);
            if ($json)
                array_push($ret, [
                    'curl_result'       => $json, 
                    'email'             => $v['email'],
                    'user_id'           => $v['user_id']                    
                ]);
            else
                array_push($ret, ['curl_result' => ['error' => 1, 'proxy_error' => '1', 'ip' => $proxy]]);
        }
        curl_close($curl);
        return ($ret);
    }
}