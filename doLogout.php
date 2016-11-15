<?php
/**
 * Class logout
 */

require_once("loginClass.php");

$login = new Login();

if($login->userIsLoggedIn())
{	
	$response = array('wasLogged' => true);
	$login->doLogout();
	echo json_encode($response);
}
else
{
	$response = array('wasNotLogged' => true);
	echo json_encode($response);
}

?>