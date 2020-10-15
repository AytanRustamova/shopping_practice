<?php 
include("includes/config.php");
$success = false;
if(isset($_GET['tokenAlfa']) && isset($_GET['tokenBetta'])){
    echo "test";
    
    $tokenS = $_GET['tokenAlfa'];
    $tokenV = $_GET['tokenBetta'];

    $sql ="SELECT * FROM token WHERE selector=:tokenAlfa";
    $query=$con->prepare($sql);
    $query->bindParam(':tokenAlfa', $tokenS);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
    

 if($tokenV == $result[0]['validator']){
     echo "test2";
  
    $success = true;
    $email = $result[0]['email'];

 }

}

 
 
 


    if(isset($_POST['newPasswordButton']) && $success==true){
        echo "test3";
        
    
        $newPw = $_POST['newPassword'];

        $newCnPw = $_POST['newConfirmPassword'];

        // Sanitization and Validation must be here.
        // If everything is true...
        $encryptedNewPw = password_hash($newPw, PASSWORD_DEFAULT ); 
        $sql ="UPDATE users SET password=:password  WHERE email =:email";
        $query=$con->prepare($sql);
        $query->bindParam(':email', $email);
        $query->bindParam(':password', $encryptedNewPw);
        $query->execute();

        $sql ="DELETE FROM token  WHERE email =:email";
        $query=$con->prepare($sql);
        $query->bindParam(':email', $email);
        $query->execute();
        header("Location: index.php");




    } else {
        echo "Your password have been not changed";
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <p>   
        <label for="password">New Password</label>
        <input id="password" name="newPassword" type="password" placeholder="Your Password" required>
        </p>
        <p>
        <label for="password">Confirm Password</label>
        <input id="password" name="newConfirmPassword" type="password" placeholder="Your Password" required>
        </p>
        <button type="submit" name="newPasswordButton">Submit</button>
    </form>
</body>
</html>