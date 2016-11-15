<?php
/**
 * Class registration
 * handles the user registration
 */

require_once("config/db.php");

class Registration
{
	/**
     * @var object $response The response send through ajax.
     */
    public $response = null;

    /**
     * @var object $db_connection The database connection
     */
    private $db_connection = null;
    /**
     * @var array $errors Collection of error messages
     */
    public $errors = array();
    /**
     * @var array $messages Collection of success / neutral messages
     */
    public $messages = array();
    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$registration = new Registration();"
     */
    public function __construct()
    {
        if (isset($_POST["User"])) {
            $this->registerNewUser();
        }
    }
    /**
     * handles the entire registration process. checks all error possibilities
     * and creates a new user in the database if everything is fine
     */
    private function registerNewUser()
    {
        if (empty($_POST['User'])) {
            $this->errors[] = "Empty Username";
        } elseif (empty($_POST['Password'])) {
            $this->errors[] = "Empty Password";
        } elseif (strlen($_POST['User']) > 64 || strlen($_POST['User']) < 2) {
            $this->errors[] = "Username cannot be shorter than 2 or longer than 64 characters";
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['User'])) {
            $this->errors[] = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters";
        } elseif (!empty($_POST['User'])
            && strlen($_POST['User']) <= 64
            && strlen($_POST['User']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $_POST['User'])
            && !empty($_POST['Password'])
        ) {
            // create a database connection
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            // change character set to utf8 and check it
            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }
            // if no connection errors (= working database connection)
    	
            if (!$this->db_connection->connect_errno) {
                // escaping, additionally removing everything that could be (html/javascript-) code
                $User = $this->db_connection->real_escape_string(strip_tags($_POST['User'], ENT_QUOTES));
                $Password = $_POST['Password'];
                // crypt the user's password with PHP 5.5's password_hash() function, results in a 60 character
                // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using
                // PHP 5.3/5.4, by the password hashing compatibility library
                $user_password_hash = password_hash($Password, PASSWORD_DEFAULT);
                // check if user or email address already exists
                $sql = "SELECT * FROM Users WHERE NMUSER = '".$User ."';";
                $query_check_User = $this->db_connection->query($sql);
                if ($query_check_User->num_rows == 1) {
                    $this->errors[] = "Sorry, that username / email address is already taken.";
                } else {
                    // write new user's data into database
                    $sql = "INSERT INTO Users (NMUSER, PSWDUSER)
                            VALUES('".$User . "', '".$user_password_hash."');";
                    $query_new_user_insert = $this->db_connection->query($sql);
                    // if user has been added successfully
                    if ($query_new_user_insert) {
                    	$this->response = array('wasRegistered' => true);
                        $this->messages[] = "Your account has been created successfully. You can now log in.";
                    } else {
                    	$this->response = array('wasRegistered' => false);
                        $this->errors[] = "Sorry, your registration failed. Please go back and try again.";
                    }
                }
            } else {
                $this->errors[] = "Sorry, no database connection.";
            }
        } else {
            $this->errors[] = "An unknown error occurred.";
        }
    }
}

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

