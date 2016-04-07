<?php

class FbAccount extends Model
{
    public $table = 'FbAccount';
    public $debug = true;

    public function getActiveTokensAndProxys()
    {
        $data = $this->find([
            'fields' => ['FbAccount.token', 'Proxy.ip'],
            'joins' => [
                [
                    'table' => 'Proxy',
                    'model' => 'Proxy',
                    'on' => ['Proxy.fbAccount_id = FbAccount.id']
                ]
            ],
            'conditions' => [
                'token_alive = 1'
            ]
        ]);
        return ($data);
    }

    public function getFirstActiveTokensAndProxys()
    {
        $data = $this->findFirst([
            'fields' => ['FbAccount.token', 'Proxy.ip'],
            'joins' => [
                [
                    'table' => 'Proxy',
                    'model' => 'Proxy',
                    'on' => ['Proxy.fbAccount_id = FbAccount.id']
                ]
            ],
            'conditions' => [
                'token_alive = 1'
            ]
        ]);
        return ($data);
    }

    public function createAccount($email, $password, $proxy_id)
    {
        $data = array(
            'email' => $email,
            'password' => $password,
            'proxy_id' => $proxy_id,
        );
        $this->create();
        $this->save($data);
    }
}