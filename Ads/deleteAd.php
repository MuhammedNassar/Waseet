<?php 
require '../Helpers/dbTransactions.php';
require '../Helpers/functions.php';
# Fetch Id .... 

  $id = $_GET['id'];
$ad = $_GET['ad'];
 $currentUser =$_SESSION['user_id'];
 $current_User_Type =$_SESSION['user_type'];
$ads=selectData("select * from ads where ad_id=$ad and user_id=$id" );


if (mysqli_num_rows($ads) >0) {
  $data = mysqli_fetch_assoc($ads);
  if ($currentUser == $data["user_id"] ||( $current_User_Type == 1 || $current_User_Type==2) ) {
  }
   insertData(" delete from ads where ad_id=$ad");

 
   header("location: ".UrlUsers("Ads/ad-details.php?id=$ad"));
  
   }else{
    header("location: ".UrlUsers("Home/"));
   }


 
  