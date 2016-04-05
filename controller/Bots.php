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
    
    public function findBotsToActive()
    {
        // write the conditions to find and return the accounts to active.
    }
    public function activeToken()
    {
        // add where conditions bellow
        $this->loadModel('FbAccount');
        $accounts = $this->FbAccount->find(array(
            'fields' => array(
                'FbAccount.id',
                'FbAccount.password',
                'FbAccount.email',
            ),
            'conditions' => array(
                'active = 0'
            )
            // add inner join here.
            /**
             * USE findBotsToActive();
             */
        ));

        foreach ($accounts as $account)
        {
            $c = Controller::loadController('Caspers');
            $c->generateToken($account);
            $c->getToken($account);    
        }
              print_r($accounts);
    }

    public function saveToken($params)
    {
        $token = $params[0];
        $account_id = $params[1];
        $this->loadModel('FbAccount');
        $this->FbAccount->updateById($account_id, array(
            'token' => $token,
            'active' => '1',
            'token_alive' => '1'
        ));
        
    }

    public function resetToken()
    {
        
    }
}