<?php 
class Product{
   private $con;
  
   public function __construct($con){
       $this->con = $con;
       
   }

   public function getProductList(){
        $sql = "SELECT * FROM products";
        $query = $this->con->prepare($sql);
        $query->execute();
     
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $html = "";
        foreach($results as $product){
            $productName = $product['product_name'];
            $product_id = $product['product_id'];
            $html = $html . "<a href='productDetails.php?id=$product_id'>
                                <li>$productName</li>
                            </a>";          
        }
        return $html;
   }
   public function getProductDetails($productId){
    $sql = "SELECT * FROM products WHERE product_id=:id";
    $query = $this->con->prepare($sql);
    $query->bindParam(":id", $productId);
    $query->execute();
    
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    $productName = $results[0]['product_name'];
    $productPrice = $results[0]['product_price'];
    $leftInStock = $results[0]['product_quantity'];
    
    $html = "";
    $html = $html . "<h3>
                         $productName
                     </h3>
                     <p>
                         Price: $productPrice
                     </p>
                     <p>
                         Left in stock: $leftInStock
                     </p>
                     <label for='quantity'>Quantity:</label>
                     <input type='number' id='quantity' name='quantity' min='1' max='$leftInStock'>
                     <button onclick=" . "addToCart('$productId')" . ">Add to Cart</button>
                     ";
     return $html; 
    }
    




    public function selectProduct($category1, $subCategory1){

        $sql = "SELECT * FROM products WHERE category_id = :category_id AND subcategory_id = :subcategory_id";
        $query = $this->con->prepare($sql);
        $query->bindParam(":category_id", $category1);
        $query->bindParam(":subcategory_id", $subCategory1);
        $query->execute();
        $selectResults = $query->fetchAll(PDO::FETCH_ASSOC);
        
        $html = "";
        foreach($selectResults as $product){
            $productName = $product['product_name'];
            $html = $html . "
                                <li>$productName</li>"
                             ;          
            }
            return $html;
    


    }





















}

   ?>

