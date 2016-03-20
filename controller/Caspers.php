<?php

class Caspers extends Controller
{
    public function getScriptPath($name)
    {
        $path = ROOT.DS.'js'.DS.$name.'.js';
        return ($path);
    } 
    
    // replace test by script path below;
    public function generateToken($account)
    {
        $file_path = $this->getScriptPath('test');
        $req = 'casperjs '. $file_path. ' '. $account->email. ' '. $account->password. ' ';
        $req .= '--proxy='.$account->ip. ' --proxy-auth=mrsoyer:tomylyjon';
        echo(exec($req));
    }

    public function getToken($account)
    {        
        $file_path = $this->getScriptPath('test');
        $req = 'casperjs '. $file_path. ' '. $account->email. ' '. $account->password. ' ';
        $req .= '--proxy='.$account->ip. ' --proxy-auth=mrsoyer:tomylyjon';
        echo(exec($req));\        
    }
}