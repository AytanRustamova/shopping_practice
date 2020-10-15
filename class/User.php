<?php
class User{
    private $con;
    private $errorArray;
    public function __construct($con){
        $this->con = $con;
        $this->errorArray = array();

    }
    public function getId($email){
        
        $sql = "SELECT * FROM users WHERE email=:email";
        $query = $this->con->prepare($sql);
        $query->bindParam(":email", $email);
        //ERROR HANDLING
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $userId = $results[0]['user_id'];
        return $userId;
    } 
    public function register($username, $email, $password, $confirmPassword) {
        $un = $this->sanitizeFormUsername($username);
        $em = $this->sanitizeFormString($email);
        $pw = $this->sanitizeFormPassword($password);
        $pw2 = $this->sanitizeFormPassword($confirmPassword);


        $this->validateUsername($un);
        $this->validatePasswords($pw, $pw2);
        $this->validateEmail($em);

    
        if(empty($this->errorArray) == true){
            return $this->insertDetailsData($username, $email, $password);
        } else{
            return false;
        }
   }

   public function getError($error){
    if(!in_array($error, $this->errorArray)){
        $error = "";
    }
    return "<span class='errorMessage'>$error</span>";
    }

    public function sanitizeFormUsername($inputText){
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText );
        return $inputText;
    }
    

    public function sanitizeFormPassword($inputText){
        $inputText = strip_tags($inputText);
        return $inputText;
    }
  
    public function sanitizeFormString($inputText){
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText );
        $inputText = ucfirst(strtolower($inputText));
        return $inputText;

    }

    public function validateUsername($un){

        if(strlen($un) > 25 || strlen($un) < 5) {
            array_push($this->errorArray, Constant::$usernameCharacters);
            return;
        }
        $sql ="SELECT * FROM users WHERE username = :username";
        $query = $this->con->prepare($sql);
        $query->bindParam(':username', $un);
        $query->execute();
        if ($query->rowcount() != 0){
            array_push($this->errorArray, Constant::$usernameTaken);
            return;
        } 
 
    }

    public function validatePasswords($pw, $pw2){
        if ($pw != $pw2) {
            array_push($this->errorArray, Constant::$passwordsDoNoMatch);
            return;
        }
        if (preg_match('/[^A-Za-z0-9]/', $pw)) {
            array_push($this->errorArray, Constant::$passwordsDoNotAlphanumeric);
            return;
        }
        if (strlen($pw) > 30 || strlen($pw) < 5) {
            array_push($this->errorArray, Constant::$passwordsCharacters);
            return;
        }

    }

    public function validateEmail($em){
        if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray, Constant::$emailInvalid);
            return;
        }

        $sql ="SELECT * FROM users WHERE email = :email";
        $query = $this->con->prepare($sql);
        $query->bindParam(':email', $em);
        $query->execute();
        if ($query->rowcount() != 0){
            array_push($this->errorArray, Constant::$emailTaken);
            return;
        } 
 
    }


    public function insertDetailsData($username, $email, $password){

        
        $encryptedPw = password_hash($password, PASSWORD_DEFAULT ); 

        $sql ="INSERT INTO users(username, email, password) VALUES (:username, :email, :password)";
        $query = $this->con->prepare($sql);
        $query->bindParam(':username', $username);
        $query->bindParam(':email', $email);
        $query->bindParam(':password', $encryptedPw);
        $result = $query->execute();
        
        if($result) {
            return true;
        } else {
            echo 'Something went wrong. Please try again later';
            return false;
        }
    }

    public function login($email, $password){

       
        //echo $hashingPassword;

        // if(password_verify($password, $hashingPassword)){
        //     echo "password is valid";
        // } else {
        //     echo "password is invalid";
        // }

        $sql = "SELECT * FROM users WHERE BINARY email=:email";
        $query = $this->con->prepare($sql);
        $query->execute(array(':email'=>$email));
        //ERROR HANDLING
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        
        
           if ($query->rowCount() > 0){
            $dbpassword = $results[0]['password'];
              
                if(password_verify($password, $dbpassword)){
                    return true;
            
                } else {
                    array_push($this->errorArray, Constant::$loginFailed);
                    return false;
                }
            } else {
                echo "password is invalid";
            }
        

    }



}





?>