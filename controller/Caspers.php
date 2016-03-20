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
        $req = 'casperjs --ssl-protocol=tlsv1 '. $file_path. ' '. $account->email. ' '. $account->password. ' ';
        //        $req .= '--proxy='.$account->ip. ' --proxy-auth=mrsoyer:tomylyjon';
        echo(exec($req));
    }

    public function getToken($account)
    {        
        $file_path = $this->getScriptPath('token_get');
        $req = 'casperjs --ssl-protocol=tlsv1 '. $file_path. ' '. $account->email. ' '. $account->password. ' '. $account->id. ' ';
        //        $req .= '--proxy='.$account->ip. ' --proxy-auth=mrsoyer:tomylyjon';
        echo(exec($req));
    }
}