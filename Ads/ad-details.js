$(document).ready(function(){	
    $("#comForm").submit(function(event){
        submitForm();
        return false;
    });
 });


function submitForm(){
    $.ajax({
       type: "POST",
       url: "ad-details.php",
       cache:false,
       data: $('#comForm').serialize(),
       success: function(response){
           $("#contact").html(response)
           $("#comMod").modal('hide');
       },
       error: function(){
           alert("Error");
       }
   });
}
