<?php

define('WEBROOT', dirname(__FILE__));
define('ROOT', dirname(WEBROOT));
define('DS', DIRECTORY_SEPARATOR);
define('CORE', ROOT.DS.'core');
define('CONFIG', ROOT.DS.'config');

require(ROOT.DS.'vendor'.DS.'autoload.php');
require(CORE.DS.'includes.php');

///////////////////////////////////////////
// create an http router to avoid that...
if (isset($_POST['token']) && isset($_POST['fb_account_id']))
{

    $token = $_POST['token'];
    $fb_account_id = $_POST['fb_account_id'];
    
    if (!empty($token) && !empty($fb_account_id))
    {
        $c = Controller::loadController('Bots');
        $c->loadModel('FbAccount');
        $c->FbAccount->updateById($fb_account_id, array(
            'token' => $token
        ));
    }
}
//////////////////////////////////////////

else if (isset($argc) && isset($argv))
{
    $r = new ShellRouter($argc, $argv);
    $r->executeAction();
}
/*
else
{
    $c = Controller::loadController('Bots');
    $c->loadModel('FbAccount');
    $accounts = array(
        array('email' => 'Tallmanajw703@gmail.com', 'password' => 'ciUsWTpi177'),
        array('email' => 'Shinani858@gmail.com', 'password' => 'eGcDqvRJ789'),
        array('email' => 'Uriasani377@gmail.com', 'password' => 'ULQdNgon701'),
        array('email' => 'Shisleribs886@gmail.com', 'password' => 'OLFqKJVM672'),
        array('email' => 'ingeregis6621@yahoo.com', 'password' => 'MIXO3Wz7247'),
      array('email' => 'carmentutor3349@yahoo.com', 'password' => 'rf60dIeK537'),
      array('email' => 'cherieanguiano3137@yahoo.com', 'password' => 'hXGifLwY725'),
      array('email' => 'halleyhopper1567@yahoo.com', 'password' => 'peHjxoa8689'),
      array('email' => 'antonettesharpe1215@yahoo.com', 'password' => 'Txck5VmK958'),
    );
   
    foreach ($accounts as $ac)
    {
        //print_r("email->".$ac['email']." pass->".$ac['password']."\n");
        $c->FbAccount->createAccount($ac['email'], $ac['password'], 0);
    }
}
*/
?>