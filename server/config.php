<?php
define('PATH', 'sym');
define('ROOT', $_SERVER["HTTP_HOST"]);
define('DS', DIRECTORY_SEPARATOR);
define('APP', ROOT.DS.PATH);
define('SERVER', APP.DS.'server');
define('JS', APP.DS.'js');

//DATABASE
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sym');

echo ('js path : '. JS);echo('<br>');
echo ('server path : '. SERVER);echo('<br>');
echo ('app path : '. APP);echo('<br>');


echo('<pre>');
var_dump($_SERVER);
echo('/<pre>');
?>