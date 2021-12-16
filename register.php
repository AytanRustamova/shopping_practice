
<?php
include("includes/config.php");
include("class/User.php");
include("class/Constant.php");
  
    $user = new User($con);

    function getInputValue($data){
        if(isset($_POST[$data])){
            echo $_POST[$data];
        } 
    }
    if(isset($_POST['registerButton'])){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
    
        $wasSuccessful = $user->register($username, $email, $password, $confirmPassword);
    
        if($wasSuccessful){
            $_SESSION['userLoggedIn'] = $user->getId($email);
            header("Location: index.php");
        }
    }




?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evergreens-Collections</title>
</head>
<body>

<form id="registerForm" action="register.php" method="POST">
    <h2>Create your free account</h2>
    <p>
    <?php echo $user->getError(Constant::$usernameCharacters);?>
    <?php echo $user->getError(Constant::$usernameTaken);?>
    <label for="username">Username</label>
    <input id="username" name="username" type="text" placeholder="username" value="<?php getInputValue('username');?>" required>
    </p>
    <p>
    <?php echo $user->getError(Constant::$emailInvalid);?>
    <?php echo $user->getError(Constant::$emailTaken);?>
    <label for="email">Email</label>
    <input id="email" name="email" type="email" placeholder="email" value="<?php getInputValue('email');?>" required>
    </p>
    <p>
    <?php echo $user->getError(Constant::$passwordsDoNoMatch);?>
    <?php echo $user->getError(Constant::$passwordsCharacters);?>
    <?php echo $user->getError(Constant::$passwordsDoNotAlphanumeric);?>
    <label for="password">Password</label>
    <input id="password" name="password" type="password" placeholder="Password" required>
    </p>
    <p>
    <label for="password">Confirm Password</label>
    <input id="password" name="confirmPassword" type="password" placeholder="Confirm Password" required>
    </p>
    <button type="submit" name="registerButton">Sign Up</button>
</form>
<a href='login.php'>Already have account? Login Now</a>