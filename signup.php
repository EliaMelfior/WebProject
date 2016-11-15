<?php
/**
 * Class registration
 * handles the user registration
 */

require_once("signupClass.php");

$registration = new Registration();

echo json_encode($registration->response);

?>

