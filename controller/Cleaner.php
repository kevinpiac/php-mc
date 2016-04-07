<?php

require ROOT.'/controller/Curl.php';


// -> Clean --> getFacebookIds --> handleFacebookResults --> getFacebookDataByIds --> (saving).

class Cleaner extends Controller
{
    public function clean()
    {
        $hour_request_count = 300;    // nombre de requete max par heure par token
        $hour_request_frequency = 50; // frequence des requetes (ex. 3 => 1 requete toute les 3 minutes)
        $nb_per_token = $hour_request_count / $hour_request_frequency;

        $this->loadModel('ToClean');
        $this->loadModel('FbAccount');
        
        $ids = [];
        $tokens = $this->FbAccount->find([
            'fields' => ['FbAccount.token', 'Proxy.ip'],
            'joins' => [
                [
                    'table' => 'Proxy',
                    'model' => 'Proxy',
                    'on' => ['Proxy.fbAccount_id = FbAccount.id']
                ]
            ],
            'conditions' => [
                'token_alive = 1'
            ]
        ]);
        $token_count = count($tokens);
        foreach ($tokens as $t)
        {
            $proxy = $t->ip;
            $datas = $this->ToClean->find([
                'limit' => $nb_per_token
            ]);
            foreach ($datas as $data)
            {
                $data->url = 'https://graph.facebook.com/search?q=' . $data->email . '&type=user&access_token=' . $t->token ;
            }
            $datas = json_decode(json_encode($datas), True);
            $ids = array_merge($ids, $this->getFacebookIds($datas, $proxy));
        }
        $this->handleFacebookResults($ids);
    }

    // voir pour traiter la blank page error !

    public function getFacebookIds($data, $proxy)
    {
        $ret = Curl::CurlOpenGraph($data, $proxy);
        // on recupere le resultat de l'openGraph et on le formate dans un joli tableau :D 
        //        print_r($ret);
        $res = [];
        foreach ($ret as $k => $v)
        {
            // Si on a une erreur de proxy on l'ajoute au tableau.
            if (isset($v['curl_result']['proxy_error']))
            {
                $arr = [
                    'error' => 1, 
                    'proxy_error' => 1, 
                    'message' => 'Empty curl result, you should check the proxy'
                ];
            }
            else
            {
                $data = json_decode($v['curl_result']);
                // Si une erreur de token (ou autre) survient on recupere le msg de fb et on ajouter l'erreur au tableau.
                if (isset($data->error))
                {
                    $arr = [
                        'error' => 1, 
                        'token_error' => 1, 
                        'message' => $data->error->message];
                } // Sinon, si on a bien recu les donnees, on les ajoute au tableau.
                else if (isset($data->data[0]))
                {
                    $arr = [
                        'email' => $v['email'],
                        'name'  => $data->data[0]->name,
                        'id'    => $data->data[0]->id
                    ];
                }
                else // Sinon, c'est que le mail n'existe pas. On ajoute email error au tableau.
                    $arr = ['error' => 1, 'email_error' => 1];
            }
            array_push($res, $arr);
        }
        return ($res);
    }

    public function getFacebookDataByIds($ids)
    {
        /////////////////////////////////////
        ///////////////////////////////////// TO DO
        //////////////////////////////////// PROXYS GETTER
       
        $proxy = 'http://163.172.247.174:80'; // Modify the getting way here.
        $base_url = 'https://graph.facebook.com/';
        $token = 'CAAAACZAVC6ygBALoCmizqafRwXZBIw141kqZBSxFAgMcTrrUScKdjI1mvsl7Ugy6t2C6VcxfPfUmfdKCEnMHEJTI3ZACgK5hH7ZAdC7ZCzu4suyZCeUNUf2HQvx29g4Wp2nbHPnnM3x1ufYukZBRPiyoRCWDiODpsvokMV8QgaLHA4ADiyNdDFx98hoofAv7p20ZD'; 
        $urls = [];
        $res = [];
        foreach ($ids as $k => $v)
        {
            $url = $base_url . $v['id'] . "?access_token=" . $token;
            $arr = ['email' => $v['email'], 'url' => $url];
            array_push($urls, $arr);
        }

        $ret = Curl::CurlOpenGraph($urls, $proxy);
        foreach ($ret as $k => $v)
        {
            $data = json_decode($v['curl_result']);
            $data->email = $v['email'];
            array_push($res, $data);
        }
        return ($res);
    }

    public function handleFacebookResults($result)
    {
        $verified = [];
        $token_errors = [];
        $email_errors = [];
        $proxy_errors = [];
        // on divise le resultat en trois tableaux
        print_r($result);
        foreach ($result as $k => $v)
        {
            if (isset($v['email_error']))
                array_push($email_errors, $v);
            else if(isset($v['token_error']))
                array_push($token_errors, $v);
            else if(isset($v['proxy_error']))
                array_push($proxy_errors, $v);
            else if(!empty($v['id']))
                array_push($verified, $v);
        }
        // Only for debug.
        $this->debug = true;
        if (isset($this->debug))
        {
            print_r($verified);
            print_r($token_errors);
            print_r($email_errors);
            
            $tk_err_count = count($token_errors);
            $mail_err_count = count($email_errors);
            $verif_count = count($verified);
            $proxy_count = count($proxy_errors);
            print_r("\n token errors: ". $tk_err_count);
            print_r("\n proxy errors: ". $proxy_count);
            print_r("\n email errors: ". $mail_err_count);
            print_r("\n succes      : ". $verif_count."\n");
        }
        return ;
        // On traite chacun des tableaux
        if (!empty($verified))
        {
            $profiles = $this->getFacebookDataByIds($verified);
            if (!empty($profiles))
            {
                $this->loadModel('People');
                $this->People->saveAllPeople($profiles);
            }
        }
        //$this->handleTokenError($token_errors);
    }
} 
/*        $allWrong = Curl::CurlOne('https://graph.facebook.com/search?q=charlestadde@icloud.com&type=user&access_token=CAAAACZAVC6ygBALG9roRwRWxa7TA8FixI3CmFL9vo3DyWeI45JeAkCC9r20ZA9vWEtVka5v50GRpTMqxHh1VxiGn73NTypBMw2Q44Cl4lY8oBBqQ5cZAB0FxPhBtbsJtsauUncWr34noPu4rEYzBbuaAVNytXzmdcrOKmmznXOwqRqe1w5K', '185.3.132.148:80');        
        $goodProxyAndWrongEmail = Curl::CurlOne('https://graph.facebook.com/search?q=charlestadde@icloud.com&type=user&access_token=CAAAACZAVC6ygBALG9roRwRWxa7TA8FixI3CmFL9vo3DyWeI45JeAkCC9r20ZA9vWEtVka5v50GRpTMqxHh1VxiGn73NTypBMw2Q44Cl4lY8oBBqQ5cZAB0FxPhBtbsJtsauUncWr34noPu4rEYzBbuaAVNytXzmdcrOKmmznXOwqRqe1w5K', '212.129.6.111:80');
        $goodProxyAndGoodEmail = Curl::CurlOne('https://graph.facebook.com/search?q=kevinpiac@gmail.com&type=user&access_token=CAAAACZAVC6ygBAJBZBwJZCt1KHCP3i9005eiYVvZBZA7ReQEZAq10Rl3QZC655fJKJUU6syFFBgHmdNvjiaPPzhHYzsZBX9YZCoKC4HCQTNdShJuE6LCToDcZClcWRvOuXrvymE1HOxYidCarHjp3XCLKAJRUvoLBNZBNCnVk4jDQTS0h2fiWfLWR9E', '212.129.6.111:80');
        print_r("allWrong:".$allWrong."\n\n");
        print_r("goodProXyAndWrongEmail:".$goodProxyAndWrongEmail."\n\n");
        print_r("goodProXyAndGoodEmail:".$goodProxyAndGoodEmail."\n\n");
        return;


*/

        /*
        $urls = [
            [
                'url' => "https://graph.facebook.com/search?q=leserbe11%40gmx.fr&type=user&access_token=CAAAACZAVC6ygBALA3akfozB0fHa1c4OZBIa1ee6Xqg8qv68fZBfxXZA6Wnz1nK6ZBVw706expSanZAvqMIPmTcBIEZB38K8gmT4wHqP9ssGyJH5FT11czUXqzZCSSdx4Aq4W0IHpcsNcKwlQGtWPscwokAsN2f0okE0IIzC7ZA8HkN1YseNVc9eGUMyzTWIFoxnKddSU82gZDZD", 
                'email' => 'wrongToken@gmail.com'
            ],
            [
                'url' => "https://graph.facebook.com/search?q=wrong%40gmx.fr&type=user&access_token=CAAAACZAVC6ygBALA3akfozB0fHa1c4OZBIa1eY77Ve6Xqg8qv68fZBfxXZA6Wnz1nK6ZBVw706expSanZAvqMIPmTcBIEZB38K8gmT4wHqP9ssGyJH5FT11czUXqzZCSSdx4Aq4W0IHpcsNcKwlQGtWPscwokAsN2f0okE0IIzC7ZA8HkN1YseNVc9eGUMyzTWIFoxnKddSU82gZDZD", 
                'email' => 'wrongEmail@gmail.com'
            ],
            [
                'url' => "https://graph.facebook.com/search?q=kevinpiac%40gmail.com&type=user&access_token=CAAAACZAVC6ygBALA3akfozB0fHa1c4OZBIa1eY77Ve6Xqg8qv68fZBfxXZA6Wnz1nK6ZBVw706expSanZAvqMIPmTcBIEZB38K8gmT4wHqP9ssGyJH5FT11czUXqzZCSSdx4Aq4W0IHpcsNcKwlQGtWPscwokAsN2f0okE0IIzC7ZA8HkN1YseNVc9eGUMyzTWIFoxnKddSU82gZDZD", 
                'email' => 'kevinpiac@gmail.com'
            ],
            [
                'url' => "https://graph.facebook.com/search?q=leserbe11%40gmx.fr&type=user&access_token=CAAAACZAVC6ygBALA3akfozB0fHa1c4OZBIa1eY77Ve6Xqg8qv68fZBfxXZA6Wnz1nK6ZBVw706expSanZAvqMIPmTcBIEZB38K8gmT4wHqP9ssGyJH5FT11czUXqzZCSSdx4Aq4W0IHpcsNcKwlQGtWPscwokAsN2f0okE0IIzC7ZA8HkN1YseNVc9eGUMyzTWIFoxnKddSU82gZDZD", 
                'email' => 'GoodEmailAndToken@gmail.com'
            ]
        ];

        */
