<?php

class Cleaner extends Controller
{

    public function getEmailsToClean()
    {
        $urls = [
            [
                'url' => "https://graph.facebook.com/search?q=leserbe11%40gmx.fr&type=user&access_token=CAAAACZAVC6ygBALA3akfozB0fHa1c4OZBIa1eY77Ve6Xqg8qv68fZBfxXZA6Wnz1nK6ZBVw706expSanZAvqMIPmTcBIEZB38K8gmT4wHqP9ssGyJH5FT11czUXqzZCSSdx4Aq4W0IHpcsNcKwlQGtWPscwokAsN2f0okE0IIzC7ZA8HkN1YseNVc9eGUMyzTWIFoxnKddSU82gZDZD", 
                'email' => 'moncul@gmail.com'
            ],
            [
                'url' => "https://graph.facebook.com/search?q=kevinpiac@gmail.com&type=user&access_token=CAAAACZAVC6ygBALA3akfozB0fHa1c4OZBIa1eY77Ve6Xqg8qv68fZBfxXZA6Wnz1nK6ZBVw706expSanZAvqMIPmTcBIEZB38K8gmT4wHqP9ssGyJH5FT11czUXqzZCSSdx4Aq4W0IHpcsNcKwlQGtWPscwokAsN2f0okE0IIzC7ZA8HkN1YseNVc9eGUMyzTWIFoxnKddSU82gZDZD", 
                'email' => 'kevinpiac@gmail.com'
            ]
        ];
        return $urls;
    }

    public function getFacebookIds()
    {
        // Maybe add Curl open graph here for more consistence.
        $proxy = 'http://163.172.247.174:80'; // Modify the getting way here.
        $urls = $this->getEmailsToClean();
        require ROOT.'/controller/Curl.php';
        $res = Curl::CurlOpenGraph($urls, $proxy);
   print_r($res);
        $res = $this->checkFacebookResult($res);
        print_r($res);
        return ($res);
    }

    public function checkFacebookResult($result)
    {
        $res = [];

        foreach ($result as $k => $v)
        {
            if (!empty($v['id']))
                array_push($res, $k);
        }
    }
} 