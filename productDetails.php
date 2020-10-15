<?php 
include("includes/header.php");
include("class/Product.php");



if(isset($_GET['id'])){
    $productId = $_GET['id'];
} else {
    header("Location: products.php");
}

$product  = new Product($con)

?>


<div>
    <?php
        echo $product->getProductDetails($productId);
    ?>
</div>


<script src="ajax/addToCart.js"></script>

<?php include("includes/footer.php") ?>