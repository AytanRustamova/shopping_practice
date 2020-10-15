<?php

include("includes/header.php");
include("class/Cart.php");


$cart = new Cart($con);



if(!isset($_SESSION['userLoggedIn'])){
    echo "aytenaytenayten";
    if(isset($_COOKIE['shoppingcart'])){ 
        $cookie = json_decode($_COOKIE['shoppingcart']);
           // echo "salam xanim necesiz?";
            foreach ($cookie as $cookieData){
            $productId = $cookieData->product_id;
            echo $productId; 
            $quantity = $cookieData->cartitem_quantity;
            echo $quantity . "/n";
            $cookieHTML = $cart->cookieProcess($productId, $quantity);
            echo $cookieHTML;
            }
        }            
} else {
    $userId = $_SESSION['userLoggedIn'];
    
    if(isset($_COOKIE['shoppingcart'])){
        $cookie = json_decode($_COOKIE['shoppingcart']);
        foreach($cookie as $cookieData){
        $productId = $cookieData->product_id;
        $quantity = $cookieData->cartitem_quantity;
        
        // print_r($cookie);
        $cartOk = $cart->checkCartforUser($userId);
        if($cartOk > 0){
            // echo "Cart var";
            $resultCart = $cart->getCartId($userId);
            $cartId = $resultCart;
            
            $cartItemOk = $cart->checkProductCartitem($productId, $cartId);
                
            
               // foreach($cookie as $cookieData){
                    if($cartItemOk == 0){

                // echo "cartitemde product yoxdur insert";
                    // print_r($cookieData);
                    
                    $productId = $cookieData->product_id;  
                    $quantity = $cookieData->cartitem_quantity;
                    $successTransfer = $cart->insertCookieDataToCartItem($productId, $cartId, $quantity);  
                    if($successTransfer){
                        unset($_COOKIE['shoppingcart']); 
                        setcookie('shoppingcart', null, -1, '/'); 
                    }else {
                        return false;
                    }
                    }
                    
            //}
            //foreach($cookie as $cookieData){
            if($cartItemOk > 0){ // Update quantity
                // echo "cartitemde product var update";
                $quantity = $cookieData->cartitem_quantity;
                $cart->updateInfoCookies($productId, $cartId, $quantity);
             }  
            
       // }
                 
        }else {
            echo "cart yoxdur";
            $cart->insertUserCart($userId);
            $resultCart = $cart->getCartId($userId);
            $cartId = $resultCart;
            $cart->insertCookieDataToCartItem($productId, $cartId, $quantity);
            
             }
            } 
        }else {  
    
        }
        echo $cart->getCartInfoHTML();

?>
    <button onclick="purchase()">Buy all</button>
<?php
    // unset($_COOKIE['shoppingcart']); 
    



     } 
    

?>

    <script src="ajax/purchase.js" ></script>

    <?php include("includes/footer.php") ?>