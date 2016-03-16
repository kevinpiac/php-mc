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
$res = $c->Card->findFirst($params);

echo '<pre>';
print_r($res);
echo '</pre>';

foreach ($res as $card)
{
	echo $card->email;
}