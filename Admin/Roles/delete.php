<?php 

require '../../helpers/db.php';


# Fetch Id .... 
$id = $_GET['id'];

$sql = "select * from users_types where user_type = $id";
$op  = mysqli_query($con,$sql);

# Check If Count == 1 
if(mysqli_num_rows($op) == 1){

    // delete code ..... 
   $sql = "delete from users_types where user_type = $id";
   $op  = mysqli_query($con,$sql);

   if($op){
       $Message = ["Message" => "Raw Removed"];
   }else{
       $Message = ["Message" => "Error try Again"];
   }


}else{
    $Message = ["Message" => "Invalid Id "];
}

   #   Set Session 
   $_SESSION['Message'] = $Message;

   header("location: index.php");

?>