<?php

include "../database.php";

$accounts = $sql->query("SELECT fb.email, fb.password, fb.id, p.ip FROM fb_accounts fb INNER JOIN proxys p ON fb.proxy_id = p.id WHERE fb.active = 1 AND fb.token_alive = 0");

//echo(exec('/usr/local/bin/phantomjs Users/kevin/Projects/sym/js/token_gen.js'));
while ($account = $accounts->fetch())
{

	$email = $account['email'];
	$password = $account['password'];
	$id = $account['id'];
	$ip = $account['ip'];

	$to_exec = "/usr/local/bin/casperjs ../js/token_get.js " . $email. " " . $password. " ". $id. " --proxy=". $ip." --proxy-auth=mrsoyer:tomylyjon";
	echo(exec($to_exec));
}

?>