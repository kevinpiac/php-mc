<?php

require ROOT.'/controller/Curl.php';

class Cleaner extends Controller
{
    public function clean()
    {
        $hour_request_count = 300;    // nombre de requete max par heure par token
        $hour_request_frequency = 30; // frequence des requetes (ex. 3 => 1 requete toute les 3 minutes)
        $nb_per_token = $hour_request_count / $hour_request_frequency;

        $this->loadModel('ToClean');
        $this->loadModel('FbAccount');
        
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
            // /////////////////////////////////
            //////////////////////////////////
            // dont forget to set datas as set
            foreach ($datas as $data)
            {
                $data->url = 'https://graph.facebook.com/search?q=' . $data->email . '&type=user&access_token=' . $t->token ;
            }
            $datas = json_decode(json_encode($datas), True);
            $ids = $this->getFacebookIds($datas, $proxy); // $data should replace $URLS.
        }
        return ($this->handleFacebookResults($ids));
    }

    // voir pour traiter la blank page error !

    public function getFacebookIds($data, $proxy)
    {
        $ret = Curl::CurlOpenGraph($data, $proxy);
        // on recupere le resultat de l'openGraph et on le formate dans un joli tableau :D 
        $res = [];
        foreach ($ret as $k => $v)
        {
            $data = json_decode($v['curl_result']);
            // si une erreur survient on set le champ 'error' a 1 et on indique un message.
            if (isset($data->error))
                $arr = ['error' => 1, 'token_error' => 1, 'message' => $data->error->message];
            else if (isset($data->data[0]))
            {
                $arr = [
                    'email' => $v['email'],
                    'name'  => $data->data[0]->name,
                    'id'    => $data->data[0]->id
                ];
            }
            else
                $arr = ['error' => 1, 'email_error' => 1];
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

        // on divise le resultat en trois tableaux
        foreach ($result as $k => $v)
        {
            if (isset($v['email_error']))
                array_push($email_errors, $v);
            else if(isset($v['token_error']))
                array_push($token_errors, $v);
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
            
            print_r("\n token errors: ". $tk_err_count);
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
