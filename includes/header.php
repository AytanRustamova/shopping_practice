<?php
    
    include("includes/config.php");


    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);


    $acceptLang = ['en', 'az'];

    $lang = in_array($lang, $acceptLang) ? $lang : 'en';



    if (isset($_GET['lang'])) {
        if(in_array($_GET['lang'], $acceptLang)){  
            $lang = $_GET['lang'];
        } else {
        }

    }




    // if(isset($_SESSION['userLoggedIn'])){
    //     $userLoggedIn = $_SESSION['userLoggedIn'];
        

    // }else{
    //     header("Location: login.php");
    // }

    if(!isset($_SESSION['lang'])) {
        $_SESSION['lang'] = "en";
    }
        else if(isset($_GET['lang']) && $_SESSION['lang'] != $_GET['lang']  && !empty($_GET['lang'])){
            if($_GET['lang'] == "en"){
            $_SESSION['lang'] = "en";
            } else if($_GET['lang'] == "az"){
                $_SESSION['lang'] = "az";
            }
    }

    echo "Choose language:" . $_SESSION['lang'];




    require_once ("language/lang." . $lang . ".php");



?>


<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>  

    <?php 
        if(isset($_SESSION['userLoggedIn'])){   //  if user is logged in 
    ?>
        <a href="account.php"><button><?php echo $language['ACCOUNT']; ?></button></a>
        <a href ="logout.php"><button><?php echo $language['LOG_OUT']; ?></button></a>
    <?php
        } else {    // user is not logged in
    ?>
        <!-- $_SERVER[PHP_SELF]  returns the name of the current page. By using this when user logs in he gets returned to the page he left off -->
        <a href="login.php?page=<?php echo basename($_SERVER['PHP_SELF']);?>"><button><?php echo $language['LOG_IN']; ?></button></a>
        <a href="register.php?page=<?php echo basename($_SERVER['PHP_SELF']);?>"><button><?php echo $language['REGISTER']; ?></button></a>
    <?php
        }
    ?>
        <!-- Does not matter whether user logged in or not  -->
        <a href="cart.php"><button><?php echo $language['GO_TO_CART']; ?></button></a>
        <a href="products.php"><button><?php echo $language['PRODUCTS']; ?></button></a>
        <a href="index.php"><button><?php echo $language['MAINPAGE']; ?></button></a>
    


    <hr/>
    


      


