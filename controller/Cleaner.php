<?php

require ROOT.'/controller/Curl.php';

define('NB_REQUEST_PER_HOUR', 300);
define('REQUEST_FREQUENCY', 50);
define('REQUEST_PER_TOKEN', NB_REQUEST_PER_HOUR / REQUEST_FREQUENCY);

define('OPEN_GRAPH_BASE_URL', 'https://graph.facebook.com/');
define('OG_SEARCH_PART1', OPEN_GRAPH_BASE_URL . 'search?q=');
define('OG_SEARCH_PART2','&type=user&access_token=');


// -> Clean --> getFacebookIds --> handleFacebookResults --> getFacebookDataByIds --> (saving).

class Cleaner extends Controller
{
    public function clean()
    {
        $this->loadModel('ToClean');
        $this->loadModel('FbAccount');
        $this->loadModel('People');
        
        $ids = [];
        $tokens = $this->FbAccount->getActiveTokensAndProxys();
        $token_count = count($tokens);

        $allemailstoclean = $this->ToClean->find([
            'conditions' => ['cleaned = 0'],
            'limit' => REQUEST_PER_TOKEN * $token_count
        ]);

        foreach ($tokens as $t)
        {
            $datas = array_splice($allemailstoclean, 0, REQUEST_PER_TOKEN);
            $proxy = $t->ip;
            foreach ($datas as $data)
            {
                $data->url = OG_SEARCH_PART1 . $data->email . OG_SEARCH_PART2 . $t->token ;
            }
            $datas = json_decode(json_encode($datas), true);
            $ids = array_merge($ids, $this->getFacebookIds($datas, $proxy));
        }
        $this->handleFacebookResults($ids);
    }

    // voir pour traiter la blank page error !
    public function getFacebookIds($data, $proxy)
    {
        $ret = Curl::CurlOpenGraph($data, $proxy);
        // on recupere le resultat de l'openGraph et on le formate dans un joli tableau :D 
        $res = [];
        foreach ($ret as $k => $v)
        {
            // Si on a une erreur de proxy on l'ajoute au tableau.
            if (isset($v['curl_result']['proxy_error']))
            {
                $arr = [
                    'error' => 1, 
                    'proxy_error' => 1, 
                    'message' => 'Empty curl result, you should check the proxy',
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
                        'id'    => $data->data[0]->id,
                        'user_id' => $v['user_id']
                    ];
                }
                else // Sinon, c'est que le mail n'existe pas. On ajoute email error au tableau.
                {
                    $arr = [
                        'error' => 1, 
                        'email_error' => 1,
                        'email' => $v['email'],
                        'user_id' => $v['user_id']
                    ];
                }
            }
            array_push($res, $arr);
        }
        return ($res);
    }

    public function getFacebookDataByIds($ids)
    {
        $this->loadModel('FbAccount');
        $t = $this->FbAccount->getFirstActiveTokensAndProxys();
        $proxy = $t->ip;
        $token = $t->token;
        $urls = [];
        $res = [];

        foreach ($ids as $k => $v)
        {
            $url = OPEN_GRAPH_BASE_URL . $v['id'] . "?access_token=" . $token;
            $arr = ['email' => $v['email'], 'user_id' => $v['user_id'], 'url' => $url];
            array_push($urls, $arr);
        }
        
        $ret = Curl::CurlOpenGraph($urls, $proxy);
        
        /***************************
         ** TO DO: SHOULD BE ABLE TO PREVENT THE ERROR IF PROXY IS DOWN
         **************************/
        foreach ($ret as $k => $v)
        {
            $data = json_decode($v['curl_result']);
            $data->email = $v['email'];
            $data->user_id = $v['user_id'];
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
        // on divise le resultat en quatre tableaux
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

        // On traite chacun des tableaux
        if (!empty($verified))
        {
            $profiles = $this->getFacebookDataByIds($verified);
            if (!empty($profiles))
            {
                $this->People->saveAllPeople($profiles);
            }
        }
        $ids = [];
        foreach ($email_errors as $k => $v)
            array_push($ids, $v['user_id']);
        $ids = implode(',', $ids);
        $query = "UPDATE ToClean as ToClean SET ToClean.cleaned = 1 WHERE user_id IN (". $ids . ")";
        $this->ToClean->query($query);
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

