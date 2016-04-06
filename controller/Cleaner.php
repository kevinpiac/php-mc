<?php

require ROOT.'/controller/Curl.php';

class Cleaner extends Controller
{

    public function getEmailsToClean()
    {
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
                'url' => "https://graph.facebook.com/search?q=leserbe11%40gmx.fr&type=user&access_token=CAAAACZAVC6ygBALA3akfozB0fHa1c4OZBIa1eY77Ve6Xqg8qv68fZBfxXZA6Wnz1nK6ZBVw706expSanZAvqMIPmTcBIEZB38K8gmT4wHqP9ssGyJH5FT11czUXqzZCSSdx4Aq4W0IHpcsNcKwlQGtWPscwokAsN2f0okE0IIzC7ZA8HkN1YseNVc9eGUMyzTWIFoxnKddSU82gZDZD", 
                'email' => 'GoodEmailAndToken@gmail.com'
            ],
            [
                'url' => "https://graph.facebook.com/search?q=leserbe11%40gmx.fr&type=user&access_token=CAAAACZAVC6ygBALA3akfozB0fHa1c4OZBIa1eY77Ve6Xqg8qv68fZBfxXZA6Wnz1nK6ZBVw706expSanZAvqMIPmTcBIEZB38K8gmT4wHqP9ssGyJH5FT11czUXqzZCSSdx4Aq4W0IHpcsNcKwlQGtWPscwokAsN2f0okE0IIzC7ZA8HkN1YseNVc9eGUMyzTWIFoxnKddSU82gZDZD", 
                'email' => 'GoodEmailAndToken@gmail.com'
            ]
        ];
        return $urls;
    }

    // voir pour traiter la blank page error !

    public function getFacebookIds()
    {
        $proxy = 'http://163.172.247.174:80'; // Modify the getting way here.
        $urls = $this->getEmailsToClean();
        $ret = Curl::CurlOpenGraph($urls, $proxy);
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
        return ($this->handleFacebookResults($res));
    }

    public function getFacebookDataByIds($ids)
    {
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
            array_push($res, $data);
        }
        $this->loadModel('People');
        //        $this->create();
        print_r($res);
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
        //        $this->debug = true;
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

        // On traite chacun des tableaux
        if (!empty($verified))
            $profile = $this->getFacebookDataByIds($verified);
        //$this->handleTokenError($token_errors);
    }
} 