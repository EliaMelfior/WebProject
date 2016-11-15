<?php
/**
 * Class checkLogin
 * handles the user's login and logout process
 */

require_once("loginClass.php");

$login = new Login();

if($login->userIsLoggedIn())
{
	$response = array('isLogged' => true);
	echo json_encode($response);
}
else
{
	$response = array('isLogged' => false);
	echo json_encode($response);
}

?>