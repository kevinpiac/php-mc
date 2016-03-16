<?php

define('WEBROOT', dirname(__FILE__));
define('ROOT', dirname(WEBROOT));
define('DS', DIRECTORY_SEPARATOR);
define('CORE', ROOT.DS.'core');
define('CONFIG', ROOT.DS.'config');

require(CORE.DS.'includes.php');

$c = new Controller();
$c->loadModel('Card');
$c->Card->hello();
$params = array(
    'fields' => array(
        'email',
        'actif',
        'desabo'
    ),
    'conditions' => array(
        'id' => '>= 2'
    ),
    'limit' => '10',
    'order' => array(
        'email' => ''
    ),
);
//$res = $c->Card->find($params);
$c->Card->id = 3;
$c->Card->save(array(
    'email' => 'testsave@gmail.com',
    'point' => '1',
    'actif' => '3',
    'heure' => '3',
    'coef' => '3',
));


echo '<pre>';
print_r($res);
echo '</pre>';
