<?php

define('WEBROOT', dirname(__FILE__));
define('ROOT', dirname(WEBROOT));
define('DS', DIRECTORY_SEPARATOR);
define('CORE', ROOT.DS.'core');
define('CONFIG', ROOT.DS.'config');

require(CORE.DS.'includes.php');

$c = new Controller();
$c->loadModel('Card');
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

// NEED TO SET THE $this->id WHEN UPDATING AND FINDING INFO.

$res = $c->Card->findById(4);
print_r($res);

$c->Card->updateById(4, array('email' => "'blabla'"));

$res = $c->Card->findById(4);
print_r($res);
