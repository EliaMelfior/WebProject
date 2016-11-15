<?php
/**
 * Class registration
 * handles the user registration
 */

require_once("signupClass.php");

$registration = new Registration();

echo json_encode($registration->response);

/*

ACESSANDO O BANCO DE DADOS :

cd /opt/lampp/bin

./mysql


DATABASE Users

TABELA Users 


CREATE TABLE Users
(
NMUSER VARCHAR(50),
PSWDUSER VARCHAR(50)
);
*/

?>

