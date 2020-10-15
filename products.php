<?php 
include("includes/header.php");
include("class/Product.php");


$product = new Product($con);


if(isset($_POST['selectButton'])){ // it will be filtered queries for selections.
    
    
    $category1 = $_POST['categoryName'];
    
    $subCategory1 = $_POST['subcategoryName'];  

    // echo $subCategory1;
    // echo $category1;
    
    // echo $subCategory1;

    echo $product->selectProduct($category1, $subCategory1);
    
}

// }
 

         $sql = "SELECT * FROM category";
          $query = $con->prepare($sql);
          $query->execute();
          $results = $query->fetchAll(PDO::FETCH_ASSOC);


          $sql = "SELECT * FROM subcategory";
          $query = $con->prepare($sql);
          $query->execute();
          $results2 = $query->fetchAll(PDO::FETCH_ASSOC);

?>
  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <ol>
    <?php
        echo $product->getProductList();
    ?>
    </ol>

    
<form action="products.php"  method="POST" >


        <label for="sel1">Category</label>

		  <select name="categoryName" id="category">
          <option value="">Select Category</option>
             <?php 
                foreach($results as $category){
                    $categoryId = $category[0]['category_id'];
                    $categoryName = $category[0]['category_name'];
                ?>
                        <option value="<?php echo $category["category_id"];?>"><?php echo $category["category_name"];?></option>
                <?php
                    echo $category;
                }
            ?>
            
            </select>

            <label for="sel2">Subcategory</label>
            <select name="subcategoryName" id="subcategory">
                <option value="" disabled selected>Choose Subcategory</option>


         <?php

            foreach($results2 as $subcategory){
                $subCategoryId = $subcategory[0]['subcategory_id'];
                $subCategoryName = $subcategory[0]['subcategory_name'];

                

        ?>         
                    <option value="<?php echo $subcategory["subcategory_id"];?>"><?php echo $subcategory["subcategory_name"];?></option> 
        <?php
            }
        ?>

            </select>

            <button type="submit" name="selectButton">Select</button>

</form>


<?php include("includes/footer.php") ?>





