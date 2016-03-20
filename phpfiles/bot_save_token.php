<?php

$token = $_POST['token'];
$fb_account_id = $_POST['fb_account_id'];

if (!empty($token) && !empty(fb_account_id))
{
    //    $c = new Controller;
    $c = Controller::loadController('Bots');
    $c->Bots->SayHello();
}
 echo ("hello bitch!");
?>