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
    static function CurlOpenGraph($params = [], $proxy = null, $header = 0)
    {
        $ret = [];
        $curl = curl_init();
        if (!empty($proxy))
        {
            curl_setopt($curl, CURLOPT_PROXY, $proxy);
            curl_setopt($curl, CURLOPT_PROXYUSERPWD, self::$proxyAuth);
        }
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, $header);
        foreach ($params as $k => $v)
        {
            curl_setopt($curl, CURLOPT_URL, $v['url']);
            $json = curl_exec($curl);
            array_push($ret, ['curl_result' => $json, "email" => $v['email']]);
        }
        curl_close($curl);
        // traitement du json pour un retour plus propre.
        $res = [];
        foreach ($ret as $k => $v)
        {
            $data = json_decode($v['curl_result']);
            $arr = [
                'email' => $v['email'],
                'name'  => $data->data[0]->name,
                'id'    => $data->data[0]->id
            ];
            array_push($res, $arr);
        }
        return ($res);
    }
}