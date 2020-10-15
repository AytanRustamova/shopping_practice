<?php 

    include("../includes/config.php");
    include("../class/User.php");
    include("../class/Cart.php");

    $cart = new Cart($con);

if(isset($_SESSION['userLoggedIn'])){
    $userId = $_SESSION['userLoggedIn'];
    $resultCart = $cart->getCartId($userId);
    $cartId = $resultCart;
     
    $orderCheck = $cart->checkOrder($userId);
    if($orderCheck > 0){
        $resultOrder = $cart->getOrderId($userId);
        $orderId = $resultOrder;
        $array = $cart->getCartInfo();
        $cartItemsProductId = $array[0]['product_id'];
        $cartItemsQuantity = $array[0]['cartitem_quantity'];
        foreach($array as $order){
            $cartItemsProductId = $order['product_id'];
            $cartItemsQuantity = $order['cartitem_quantity'];
            $cart->insertOrderItemFromCartItem($cartItemsProductId, $orderId, $cartItemsQuantity);
        }
        $cart->deleteCartItems($cartId);
        $productArray = $cart->getOrdersDataForProduct($orderId, $orderItemStatus = "");
        
        foreach($productArray as $quantArray){
            $orderProductId = $quantArray['product_id'];
            $orderProductQuantity = $quantArray['orderitem_quantity'];
            $cart->updateProductQuantity($orderProductId, $orderProductQuantity);
        }

    }else {
        $cart->insertOrder($userId);
        $resultOrder = $cart->getOrderId($userId);
        $orderId = $resultOrder;
        // there is problem with if statement, after insert userId to order table, then it should be added orderitem table. 
        $array = $cart->getCartInfo();
        $cartItemsProductId = $array[0]['product_id'];
        $cartItemsQuantity = $array[0]['cartitem_quantity'];
        foreach($array as $order){
            $cartItemsProductId = $order['product_id'];
            $cartItemsQuantity = $order['cartitem_quantity'];
            $cart->insertOrderItemFromCartItem($cartItemsProductId, $orderId, $cartItemsQuantity);
    }
    $cart->deleteCartItems($cartId);
    $productArray = $cart->getOrdersDataForProduct($orderId, $orderItemStatus = "");
    foreach($productArray as $quantArray){
        $orderProductId = $quantArray['product_id'];
        $orderProductQuantity = $quantArray['orderitem_quantity'];
        $cart->updateProductQuantity($orderProductId, $orderProductQuantity);
    }
  }
}else {
    // You have to login;
    header("Location: login.php");

}


?>