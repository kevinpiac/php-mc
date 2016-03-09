<?php

//DATABASE
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'sym');

try
{
    $sql = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
}
catch (Exception $e)
{
  die('Error: ' . $e->getMessage());
}

?>