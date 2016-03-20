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
            'fields' => array('FbAccount.email'),
            'join' => array(
                'table' => 'proxys',
                'model' => 'Proxy',
                'fields' => array('Proxy.ip', 'Proxy.expire'),
                'on' => array(
                    'id =' => 'coucou'
                )
            ),
            ));


        $id = 3;
            $accounts = $this->FbAccount->find(array(
                'fields' => array('email'),
                'conditions' => array(
                    'id =' => $id,
                    'id = (0 + 1)',
                )
            ));
            //        print_r($accounts);
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