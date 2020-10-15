<?php

class Cart {

    private $con;

    public function __construct($con){
        $this->con = $con;

    }


    public function getCartId($userId){
        // echo "55555555555";
        $sql = "SELECT * FROM cart WHERE user_id = :user_id";   
        $query = $this->con->prepare($sql); 
        $query->bindParam(':user_id', $userId); 
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $cartId= $results[0]['cart_id'];
        
        return $cartId;
    }

  
    public function checkQuantityOfProducts($productId){  
        
        $sql="SELECT * FROM products WHERE product_id = :product_id";
        $query=$this->con->prepare($sql);
        $query->bindParam(":product_id" , $productId);
        $query->execute();
        $result=$query->fetchAll(PDO::FETCH_ASSOC);
        $quantity = $result[0]['product_quantity'];
        // print_r($result);
        return $quantity;

    }

    public function checkCartforUser($userId){
        // echo "naniiiiii";
        $sql="SELECT * FROM cart WHERE user_id=:user_id";
        $query=$this->con->prepare($sql);
        $query->bindParam(":user_id" , $userId);
        $query->execute();
        
        if($query->rowCount() > 0){
            return 1;
        } // Error handling;

    }

    public function updateQuantityOfCartItems($quantity, $cart_id, $product_id){
        $sql = "UPDATE cartitem SET cartitem_quantity = cartitem_quantity + :cartitem_quantity WHERE product_id = :product_id AND cart_id=:cart_id";
        $query = $this->con->prepare($sql);
        $query->bindParam(':product_id', $product_id);
        $query->bindParam(':cart_id', $cart_id);
        $query->bindParam(':cartitem_quantity', $quantity);

        $result = $query->execute();
        return $result;
    }
    
    public function insertUserCart($userId){
        
        $sql ="INSERT INTO cart(user_id) VALUES (:user_id)";
        $query = $this->con->prepare($sql);
        $query->bindParam(':user_id', $userId);
         $query->execute();
       
    }
  

    public function checkProductCartitem($productId, $cartId){
        $sql="SELECT * from cartitem WHERE product_id = :product_id AND cart_id=:cart_id";
        $query= $this->con->prepare($sql);
        $query->bindParam(":product_id", $productId);
        $query->bindParam(":cart_id", $cartId);
        $query->execute();
      
        if($query->rowCount() > 0){
            return 1;
        } // Error handling;
       

    }

    public function insertCartItem($productId, $cartId, $quantity){

        $sql="INSERT INTO cartitem(product_id, cart_id, cartitem_quantity) VALUES (:product_id, :cart_id, :cartitem_quantity)";
        $query= $this->con->prepare($sql);
        $query->bindParam(":product_id", $productId);
        $query->bindParam(":cart_id", $cartId);
        $query->bindParam(":cartitem_quantity", $quantity);
        $result = $query->execute();
        return $result;
    }

    public function getCartInfo(){
        $userId = $_SESSION['userLoggedIn'];
        $cartId = $this->getCartId($userId);
        
        $sql = "SELECT * FROM cartitem WHERE cart_id = :cart_id";
        $query1 = $this->con->prepare($sql);
        $query1->bindParam(":cart_id", $cartId);
        $query1->execute();
        $resultsCartItems = $query1->fetchAll(PDO::FETCH_ASSOC);
        return $resultsCartItems;   
    }


    public function getCartInfoHTML(){
        $html = "";
        $resultsCartItems = $this->getCartInfo();
        foreach($resultsCartItems as $cartItem){
            $cartItemsProductId = $cartItem['product_id'];
            $cartItemsQuantity = $cartItem['cartitem_quantity'];
           
        
            $sql="SELECT * FROM products WHERE product_id = :product_id";
            $query2 = $this->con->prepare($sql);
            $query2->bindParam(":product_id", $cartItemsProductId);
            $query2->execute();  

            $resultsProduct = $query2->fetchAll(PDO::FETCH_ASSOC); 

            $productName = $resultsProduct[0]['product_name'];
            $productPrice = $resultsProduct[0]['product_price'];
        
        $html = $html . "<h3>
                            $productName
                        </h3>
                        <p>
                            Price: $productPrice
                        </p>
                        <p>
                            Quantity: $cartItemsQuantity
                        </p>
                        <button>Delete</button>";
                        
        
        }
        return $html;
    
    }

    public function checkOrder($userId){
        $sql="SELECT * FROM orders WHERE user_id = :user_id";
        $query = $this->con->prepare($sql);
        $query->bindParam(":user_id", $userId);
        $query->execute();

        if($query->rowCount() > 0){
            return 1;
        }

    }

    public function getOrderId($userId){
        $sql = "SELECT * FROM orders WHERE user_id = :user_id";   
        $query = $this->con->prepare($sql); 
        $query->bindParam(':user_id', $userId); 
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $orderId= $results[0]['order_id'];
        return $orderId;

    }

    public function insertOrder($userId){
        $sql ="INSERT INTO orders(user_id) VALUES (:user_id)";
        $query = $this->con->prepare($sql);
        $query->bindParam(':user_id', $userId);
        $query->execute();

    }

    public function insertOrderItemFromCartItem($cartItemsProductId, $orderId, $cartItemsQuantity){
        $sql="INSERT INTO orderitem(product_id, order_id, orderitem_quantity) VALUES (:product_id, :order_id, :orderitem_quantity)";
        $query= $this->con->prepare($sql);
        $query->bindParam(":product_id", $cartItemsProductId);
        $query->bindParam(":order_id", $orderId);
        $query->bindParam(":orderitem_quantity", $cartItemsQuantity);
        $result = $query->execute();
        return $result;
    }

    public function deleteCartItems($cartId){
        $sql="DELETE FROM cartitem WHERE cart_id = :cart_id";
        $query =  $this->con->prepare($sql);
        $query->bindParam(":cart_id", $cartId);
        $query->execute();
    }

    public function getOrdersDataForProduct($orderId, $orderItemStatus = ""){
        $sql="SELECT * FROM orderitem WHERE order_id = :order_id AND orderitem_status = :orderitem_status";
        $query =  $this->con->prepare($sql);
        $query->bindParam(":order_id", $orderId);
        $query->bindParam(":orderitem_status", $orderItemStatus);

        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    

    public function updateProductQuantity($orderProductId, $orderProductQuantity){
        $sql="UPDATE products SET product_quantity = product_quantity - :product_quantity WHERE product_id = :product_id";
        $query = $this->con->prepare($sql);
        $query->bindParam(":product_quantity", $orderProductQuantity );
        $query->bindParam(":product_id", $orderProductId );
        $query->execute();
        // $query->fetchAll(PDO::FETCH_ASSOC);
        // return $query;  
    }

    public function cookieProcess($productId, $quantity){

        $sql="SELECT * FROM products WHERE product_id = :product_id";
        $query=$this->con->prepare($sql);
        $query->bindParam("product_id", $productId );
        $query->execute();
        $cookieResult = $query->fetchAll(PDO::FETCH_ASSOC);
        $html = "";
        foreach($cookieResult as $cookieData){
        $productName = $cookieData['product_name'];
        $productPrice = $cookieData['product_price'];
        $productQuantity= $cookieData['product_quantity'];
        $html = $html . "<li>
                            <h3>$productName</h3>
                            <p> Product Price: $productPrice azn</p>
                            <p class='quantity'> Quantity: $quantity</p>
                            <p> Left in stock:  $productQuantity </p>
                        </li>";
        } return $html;

    }

    public function insertCookieDataToCartItem($productId, $cartId, $quantity){
        $sql="INSERT INTO cartitem(product_id, cart_id, cartitem_quantity) VALUES (:product_id, :cart_id, :cartitem_quantity)";
        $query= $this->con->prepare($sql);
        $query->bindParam(":product_id", $productId);
        $query->bindParam(":cart_id", $cartId);
        $query->bindParam(":cartitem_quantity", $quantity);
        $result = $query->execute();
        print_r($result);
        return $result;
        
    }

    public function updateInfoCookies($productId, $cartId, $quantity){
        $sql = "UPDATE cartitem SET cartitem_quantity = cartitem_quantity + :cartitem_quantity WHERE product_id = :product_id AND cart_id=:cart_id";
        $query = $this->con->prepare($sql);
        $query->bindParam(':product_id', $productId);
        $query->bindParam(':cart_id', $cartId);
        $query->bindParam(':cartitem_quantity', $quantity);

        $query->execute();
        // return $result;

    }
    
   
    


    
    

    
    

}

?>