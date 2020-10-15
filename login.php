
<?php
    include("includes/config.php");
    include("class/User.php");
    include("class/Constant.php");

    $user = new User($con);


    if(isset($_POST['loginButton'])){
   
        $email = $_POST['loginEmail'];
        $password = $_POST['loginPassword'];
        
        $userId  = $user->getId($email);
        $wassuccesful = $user->login($email, $password);
        
        if($wassuccesful){
            $_SESSION['userLoggedIn'] = $userId;
            header("Location: index.php" );
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
<form id="loginForm" action="login.php" method="POST">
    <h2>Login Your Account</h2>
    <p>
    <?php echo $user->getError(Constant::$loginFailed);?>
    <input type="hidden" name="redirect" value="<?php echo $page; ?>">
    <label for="loginEmail">Email</label>
    <input id="loginEmail" name="loginEmail" type="email" placeholder="Enter your email" required>
    </p>
    <p>
    <label for="loginPassword">Password</label>
    <input id="loginPassword" name="loginPassword" type="password" placeholder="Enter your password" required>
    </p>
    <button type="submit" name="loginButton">Log In</button>
    <a href='register.php'> No Account? Register now</a>

</form>
<a href="forgotPassword.php">Forgot Password?</a>
    
</body>
</html>
