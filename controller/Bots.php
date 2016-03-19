<?php

class Bots extends Controller
{
    public function sayHello($params)
    {
        if (!empty($params))
        {
            echo "Hello! Params are : ";
            foreach ($params as $param)
                echo $param. " ";
        }
        else
        {
            echo "Are you kiding me bro ? You passed no param...";
        }
    }

    public function activeToken()
    {
        $this->loadModel('Card');
        $accounts = $this->Card->findByQuery("SELECT * FROM Card");
        // add the real query above. This query should select the account to activate.

        foreach ($accounts as $ac)
        {
            $to_exec = "/usr/local/bin/casperjs ../js/token_gen.js " . $ac->email. " " . $ac->password. " --proxy=". $ac->ip." --proxy-auth=mrsoyer:tomylyjon";
            echo(exec($to_exec));
        }
    }

    public function saveToken()
    {
        // WHAT I WANT TO WRITE :

        $this->loadModel('Bot');
        $this->Bot->find('conditions here')->contain('Table');
        
    }

    public function resetToken()
    {
        
    }
}