<?php

try
{
  $sql = new PDO('mysql:host=localhost;dbname=domaine-name;charset=utf8', 'domaine', '84andoacj-T');
}
catch (Exception $e)
{
  die('Error: ' . $e->getMessage());
}


//$token = $_POST['token'];
$token = ";lj;lkj;lkj;lkj";
if (!empty($token))
{
  $req = $sql->prepare("UPDATE fb_accounts SET token = :token WHERE id = :id");
  $req->execute(array(
		      'token' => $token, 
		      'id' => 1 
		      )
 );

}
?>