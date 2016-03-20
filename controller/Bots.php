<?php

class Bots extends Controller
{
    public function sayHello($params)
    {
        if (!empty($params))
        {
            print_r("Hello! Params are :\n");
            foreach ($params as $param)
                print_r($param. " \n");
        }
        else
        {
            print_r("Are you kiding me bro ? You passed no param...\n");
        }
    }

    public function activeToken()
    {
        $this->loadModel('FbAccount');
        $accounts = $this->FbAccount->find(array(
            'fields' => array(
                'FbAccount.id',
                'FbAccount.password',
                'FbAccount.email',
                'Proxy.ip'
            ),
            'join' => array(
                'model' => 'Proxy',
                'table' => 'proxys',
                'on' => array('FbAccount.proxy_id = Proxy.id')
            )
        ));

        foreach ($accounts as $account)
        {
            $c = Controller::loadController('Caspers');
            $c->generateToken($account);
            $c->getToken($account);
        }
    }

    public function saveToken($params)
    {
        $token = $params[0];
        $account_id = $params[1];
        $this->loadModel('FbAccount');
        $this->FbAccount->updateById($account_id, array(
            'token' => $token,
            'active' => 1,
            'token_alive' => 1
        ));
    }

    public function resetToken()
    {
        
    }
}