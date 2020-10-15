function purchase(){
    $.post('Processors/purchase.php')
    .done(function(response){
        alert("success");
        alert(response);
       
        if(response){
            // var list = document.getElementsByClassName('quantity');
            // for(var i = 0; i < list.length(); i++){
            //     list[i].innerHTML = "Quantity: 0"
            // }
            console.log(response);
            
        }
        // We make one more ajax request if the first one is successfull. This second ajax will tell cart.php to clean its cart
        // var reset = "reset";
        // $.post('cart.php', {reset:reset});
        
    });
}