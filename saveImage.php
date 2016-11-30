<?php
/**
 * Class login
 * handles the user's login and logout process
 */

require_once("config/db.php");

class saveImage
{
    private $db_connection = null;
    public $errors = array();
    public $messages = array();
    public function __construct()
    {
    	session_start();
        if (isset($_POST["myObject"])) {
            $this->saveImage();
        }
    }

    private function saveImage()
    {
        if (empty($_POST['myObject'])) {
            $this->errors[] = "There was no image composition path to be recorded";
        } 
        else{
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }

            if (!$this->db_connection->connect_errno) {
                $User = $this->db_connection->real_escape_string($_POST['myObject']);

                // Pega o ID do usuário
                $nmUser = $_SESSION['User'];
                $getUserId = "SELECT IDUSER FROM Users WHERE NMUSER LIKE '$nmUser' ";
                $userId = mysqli_fetch_array($this->db_connection->query($getUserId));
                $userId = $userId['IDUSER'];

                // Cria uma imagem no banco
                $insertImage = "INSERT INTO IMAGEM(IDUSER, RATINGIMAGEM)
                        VALUES ($userId, 0)";
                $insertResult = $this->db_connection->query($insertImage);

                // Cria os passos da imagem no banco
                $imageComposition = explode("^",$_POST['myObject']);
                //$imageComposition[0] = X Values;
                //$imageComposition[1] = Y Values;
                //$imageComposition[2] = Drag Values;
                $xValues = array($imageComposition[0]);
                foreach($xValues as $values)
                {
                     $insertImageComposition = "INSERT INTO IMAGECOMPOSITION(IDIMAGEM, XPOSITION, YPOSITION, HASDRAG)
                        VALUES ()";
                     $resultOfImageInsertion = $this->db_connection->query($insertImageComposition);

                	error_log(print_r($imageComposition[0],1));
                	error_log(print_r($imageComposition[1],1));
                	error_log(print_r($imageComposition[2],1));
                }


                //Inserir na tabela os caminhos
                $insertImageComposition = "INSERT INTO IMAGECOMPOSITION(IDIMAGEM, XPOSITION, YPOSITION, HASDRAG)
                        VALUES ()";
                $result_of_login_check = $this->db_connection->query($insertImageComposition);
				
            } else {
                $this->errors[] = "Database connection problem.";
            }
        }
    }
}

$saveImage = new saveImage();


?>

