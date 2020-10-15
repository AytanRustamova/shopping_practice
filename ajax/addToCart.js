//?When user click Add to cart button, it should be checked users have cart row in cart table, If there is not row for users, it should be added(insert) to cart table then cartitem table, because we will store product_id and cart_id in cartitem table. If user have row in cart table, we just increase quantity of product in cartitem table.


function addToCart(productId){
  
    var quantity = document.getElementById("quantity").value;
    // ! console.log("THIS IS PRODUCT ID");

    //  console.log(productId);
    // if quantity is not entered by user default to 1
    if(quantity == null || quantity == 0 ){
        // Prompt error message which indicates that user should enter quantity
        quantity = 1;  
    }

    $.ajax({
        url:"Processors/cartProcess.php",   // script location
        method:"POST",            // if you use post, then get the values from $_POST
        data:{
           productId: productId,
           quantity:quantity

         },
         success:function(data)          //success call
         {
          console.log(data);
         }
     });
}
