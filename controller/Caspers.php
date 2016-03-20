<?php

class Caspers extends Controller
{
    public function getScriptPath($name)
    {
        $path = ROOT.DS.'js'.DS.$name.'.js';
        return ($path);
    } 
    
    public function generateToken($account)
    {
        $file_path = $this->getScriptPath('token_gen');
        $req = 'casperjs '. $file_path. ' '. $account->email. ' '. $account->password. ' ';
        $req .= '--proxy='.$account->ip. ' --proxy-auth=mrsoyer:tomylyjon';
        print_r("GEN->".$req."\n");
        echo(exec($req));
    }

    public function getToken($account)
    {        
        $file_path = $this->getScriptPath('token_get');
        $req = 'casperjs '. $file_path. ' '. $account->email. ' '. $account->password. ' ';
        $req .= '--proxy='.$account->ip. ' --proxy-auth=mrsoyer:tomylyjon';
        print_r("GET->".$req);

        echo(exec($req));
    }
}