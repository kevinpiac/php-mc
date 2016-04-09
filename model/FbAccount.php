<?php

class FbAccount extends Model
{
    public $table = 'FbAccount';
    public $debug = true;

    public function findAccountByToken($fields, $token)
    {
        $ret = $this->findFirst([
            'fields' => $fields,
            'conditions' => [
                'token =' => $token
            ]
        ]);
        return ($ret);
    }

    public function setTokenDownByToken($token)
    {
        $query = "UPDATE FbAccount SET token_alive = 0 WHERE token = ";
        $query .= "'".$token. "'" ;
        
        $this->query($query);
    }
    
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
                'token_alive = 1',
                'Proxy.down = 0',
                'Proxy.expiry > CURDATE()'
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
                'token_alive = 1',
                'Proxy.down = 0',
                'Proxy.expiry > CURDATE()'
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