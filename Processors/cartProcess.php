<?php 

include("../includes/config.php");
include("../class/Product.php");
include("../class/Cart.php");
include("../class/User.php");



if(isset($_GET['id'])){
    $productId = $_GET['id'];
} else {
    // echo "necesiz?";
    // header("Location: ../products.php");
}

if(isset($_POST['productId'])){
    $productId = $_POST['productId'];
    $quantity = $_POST['quantity'];
   
    $user = new User($con);
    $product = new Product($con);
    $cart = new Cart($con);

    
    if(isset($_SESSION['userLoggedIn'])){ // if user already log in 
        $userId = $_SESSION['userLoggedIn'];
        $checkQuantity=$cart->checkQuantityOfProducts($productId);
        if($checkQuantity > 0){ // check products quantity in stock
            // echo "product var";
            $cartOk = $cart->checkCartforUser($userId);
            if($cartOk > 0){  // User have cart row in cart table, that's why we should check caritem table.
                $resultCart = $cart->getCartId($userId);
                $cartId = $resultCart;
                // echo "cart var";
                $cartItemOk = $cart->checkProductCartitem($productId, $cartId);
                echo $cartItemOk;
                if($cartItemOk == 0){ // User don't have with this product in caritem Table, we check cartitem table with productId and user's cartId. That's why we should insert row with this data.
                    echo "cartItem yoxdu insert";
                $cart->insertCartItem($productId, $cartId, $quantity);
                }else{ // users add their cart in this product previously. we only need to update quantity of product.
                    // echo "cartItem var update";
                $cart->updateQuantityOfCartItems($quantity, $cartId, $productId);
                }
            }else{ // Users haven't added products previously. There is no row belong to user in cart table. That's why we should to insert to cart table,then add to cartitem table.
            //    echo "cart yoxdu insert";
                $cart->insertUserCart($userId);
                // echo $userId;
                $resultCart = $cart->getCartId($userId);
                $cartId = $resultCart;
                $cart->insertCartItem($productId, $cartId, $quantity);
            }
        }else { 
            echo "There is not this product in stock";
        }

    }else{ // if user don't log in, we should create cookie cart to save product data.
        // echo "salam";
        if(isset($_COOKIE['shoppingcart'])){ 
            $exist = false; 
            // echo "cookie cart var";
            $cookie = json_decode($_COOKIE['shoppingcart']);
                foreach($cookie as $cookieData){
                    print_r($cookieData);
                    if($productId == $cookieData->product_id){
                        // echo "eyni product add olunur";
                    $cookieData->cartitem_quantity = $cookieData->cartitem_quantity + $quantity;
                    $exist = true; 
                    setcookie("shoppingcart", json_encode($cookie), time()+604800,"/", $_SERVER['SERVER_NAME'] );
                }
            }
            
                if(!$exist){
                    // echo "salam";
                echo "ferqli product add olunur";
                $item = array("product_id" => $productId, "cartitem_quantity" => $quantity);
                array_push($cookie, $item);
                print_r($cookie);
                setcookie("shoppingcart", json_encode($cookie), time()+604800,"/", $_SERVER['SERVER_NAME'] );
                } 
            
        }else{
        // echo "cookie cart yoxdur";
        $item = array("product_id" => $productId, "cartitem_quantity" => $quantity);
        $cookie = array();
        array_push($cookie, $item);
        setcookie("shoppingcart", json_encode($cookie), time()+604800,"/", $_SERVER['SERVER_NAME'] );
        // print_r($cookie);    
        }
    }
}

?>