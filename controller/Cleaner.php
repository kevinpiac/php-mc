<?php

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
            ]
        ];
        return $urls;
    }

    // voir pour traiter la blank page error !

    public function getFacebookIds()
    {
        // Maybe add Curl open graph here for more consistence.
        $proxy = 'http://163.172.247.174:80'; // Modify the getting way here.
        $urls = $this->getEmailsToClean();
        require ROOT.'/controller/Curl.php';
        $res = Curl::CurlOpenGraph($urls, $proxy);

        return ($this->handleFacebookResults($res));
    }

    public function getFacebookDataByIds()
    {
        
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

        // On traite chacun des tableaux
        
        //        $this->getFacebookDataByIds($verifieds);
        //$this->handleTokenError($token_errors);
    }
} 