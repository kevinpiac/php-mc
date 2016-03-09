<?php
define('PATH', 'sym');
define('ROOT', $_SERVER["HTTP_HOST"]);
define('DS', DIRECTORY_SEPARATOR);
define('APP', ROOT.DS.PATH);
define('PHP', APP.DS.'phpfiles');
define('JS', APP.DS.'js');

//DATABASE
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sym');

echo ('js path : '. JS);echo('<br>');
echo ('server path : '. PHP);echo('<br>');
echo ('app path : '. APP);echo('<br>');


echo('<pre>');
var_dump($_SERVER);
echo('/<pre>');
?>