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
$res = $c->Card->find(array(
    'fields' => array(
        'email',
        'actif',
        'desabo'
    ),
    'conditions' => array(
        'actif' => '>= 1',
        'id' => 'BETWEEN 2 AND 10'
    ),
));
echo '<pre>';
print_r($res);
echo '</pre>';

foreach ($res as $card)
{
	echo $card->email;
}