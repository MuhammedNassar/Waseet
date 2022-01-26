<?php 

require '../../helpers/db.php';


# Fetch Id .... 
$id = $_GET['id'];

$sql = "select * from ads where ad_id = $id";
$op  = mysqli_query($con,$sql);

$data = mysqli_fetch_assoc($op);

# Check If Count == 1 
if(mysqli_num_rows($op) == 1){

    // delete code ..... 
   $sql = "delete from ads where ad_id = $id";
   $op  = mysqli_query($con,$sql);

   if($op){

    unlink('../../uploads/'.$data['image']);

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