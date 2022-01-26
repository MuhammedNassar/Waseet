<?php 
require '../Helpers/dbTransactions.php';
require '../Helpers/valid.php';
# Fetch Id .... 

  $id = $_GET['id'];
$ad = $_GET['ad'];
 $currentUser =$_SESSION['user_id'];
 $current_User_Type =$_SESSION['user_type'];
$comment=selectData("Select * from comments where comment_id=$id" );


if (mysqli_num_rows($comment) >0) {
  $data = mysqli_fetch_assoc($comment);
  if ($currentUser == $data["user_id"] || $current_User_Type !=3 ) {
  }
   insertData(" delete from comments where comment_id=$id");

 
   header("location: ".UrlUsers("Ads/ad-details.php?id=$ad"));
  
   }else{
    header("location: ".UrlUsers("Ads/ad-details.php?id=$ad"));
   }


 
  

?>