<?php 

require '../../Helpers/db.php';

if(!isset($_SESSION['user_type'])){
    
        header("Loction :".UrlUsers("home/"));

}
// Rights($_SESSION['user_type']);
    /*
else{
    header("Loction :".UrlUsers("home/"));
}
*/

# Fetch Id .... 
$id = $_GET['id'];

$sql = "select * from sections where section_id = $id";
$op  = mysqli_query($con,$sql);

# Check If Count == 1 
if(mysqli_num_rows($op) == 1){

    // delete code ..... 
   $sql = "delete from sections where section_id = $id";
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