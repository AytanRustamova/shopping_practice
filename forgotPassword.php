<?php 
include("includes/config.php");
// when user don't fill email field and press submit, there're can be errors so we also need to check if email field is set as well. Implement in the future.
if(isset($_POST['forgotPassword'])){ 
    $email = $_POST['resetEmail'];
    // 1. We check this user is already registered or not. if the user that not registered the website we can't send link them.
    // 2. After checking user exist, we going to tokens table then if there's row about this user we should update tokens inside of new tokens. in this time, we are going to check email exist with selector tokens. 
    // 3.  Then if user email exist in tokens table, we should send link to users.
    
    $sql ="SELECT * FROM users WHERE email = :email";
    $query=$con->prepare($sql);
    $query->bindParam(':email', $email);
    $query->execute();

    if($query->rowCount() == 1){
     
        $valid = "123456789";
        $select = "12345";

        $sql ="SELECT * FROM token WHERE email = :email";
        $query=$con->prepare($sql);
        $query->bindParam(':email', $email);
        $query->execute();
         
        if($query->rowCount() == 1){
            

            // Update 
            $sql ="UPDATE token SET selector=:selector, validator=:validator WHERE email =:email";
            $query=$con->prepare($sql);
            $query->bindParam(':email', $email);
            $query->bindParam(':selector', $select);
            $query->bindParam(':validator', $valid);
            $query->execute();

        }else{
            // insert 
            
            $sql ="INSERT INTO token(email, selector, validator) VALUES(:email, :tokenAlfa, :tokenBetta)";
            $query=$con->prepare($sql);
            $query->bindParam(':email', $email);
            $query->bindParam(':tokenAlfa', $select);
            $query->bindParam(':tokenBetta', $valid);
            $query->execute();
        }
        // this link should come from email.
        $url ="<a href='http://localhost/shopping_practice/forgotPasswordHandler.php?tokenAlfa=$select&tokenBetta=$valid'> link </a>";
        echo $url;

    }else {
        echo "You haven't registered website";
    }

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
        <label for="resetPassword">Enter Your Email</label>
        <input id="resetPassword" name="resetEmail" type="text" placeholder="e.g. bartSimpson" required>
        </p>
        <button type="submit" name="forgotPassword">Submit</button>
    </form>
</body>
</html>