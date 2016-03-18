<?php

define('WEBROOT', dirname(__FILE__));
define('ROOT', dirname(WEBROOT));
define('DS', DIRECTORY_SEPARATOR);
define('CORE', ROOT.DS.'core');
define('CONFIG', ROOT.DS.'config');

require(CORE.DS.'includes.php');
/*
$c = new Controller();
$c->loadModel('Card');
$params = array(
    'fields' => array(
        'email',
        'actif',
        'id'
    ),
    'conditions' => array(
        'id' => '>= 2'
    ),
    'limit' => '10',
    'order' => array(
        'email' => ''
    ),
);

print_r("BEFORE : \n this id ->".$c->Card->id."\n");

$res = $c->Card->findFirst($params);
print_r($res);
print_r("this id ->".$c->Card->id);

$c->Card->save(array('email' => '"coucou"'));

$res = $c->Card->findFirst($params);
print_r($res);
print_r("this id ->".$c->Card->id);

$c->Card->create();
$c->Card->save(array("email" => "'test'", 'id' => '999999'));
$res = $c->Card->findById(999999);
print_r($res);
*/

$router = new ShellRouter($argc, $argv);
print_r("Ctrl ->".$router->controller."\n");
print_r("Action ->".$router->action."\n");
foreach ($router->params as $param)
{
    print_r("Param ->".$param."\n");
}

$c = new Controller();
$c->loadController($router->controller);

$c->($router->action)();
