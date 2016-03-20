<?php

class FbAccount extends Model
{
    public $table = 'fb_accounts';
    public $debug = true;

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