<?php

include "../database.php";

$request = $sql->query('SELECT * FROM fb_accounts WHERE active = 0');

//echo(exec('/usr/local/bin/phantomjs Users/kevin/Projects/sym/js/token_gen.js'));


while ($data = $request->fetch())
{
	$to_exec = "/usr/local/bin/casperjs ../js/token_gen.js " . $data['email']. " " . $data[password];
	echo(exec($to_exec));
	$to_exec = "/usr/local/bin/casperjs ../js/token_get.js " . $data['email']. " " . $data[password] . " " . $data[id];
	echo(exec($to_exec));
}

?>