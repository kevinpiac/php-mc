<?php

define('WEBROOT', dirname(__FILE__));
define('ROOT', dirname(WEBROOT));
define('DS', DIRECTORY_SEPARATOR);
define('CORE', ROOT.DS.'core');
define('CONFIG', ROOT.DS.'config');

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

?>