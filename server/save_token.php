<?php

//server kevin$ casperjs script.js velardegav519@gmail.com UQBpqmBr384 1 --proxy=185.3.132.148:80 --proxy-auth=mrsoyer:tomylyjon
include 'database.php';

$token = $_POST['token'];
$fb_account_id = $_POST['fb_account_id'];

if (!empty($token) && !empty(fb_account_id))
{
  $req = $sql->prepare("UPDATE fb_accounts
						SET token = :token, active = :active
						WHERE id = :id");
  $req->execute(array(
		      'token' => $token,
		      'id' => $fb_account_id,
			  'active' => 1
		      )
 );
 $sql = null;
 echo ("hello");
}
?>