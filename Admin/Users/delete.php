<?php

require '../../helpers/db.php';
require '../../helpers/functions.php';
if(isset($_SESSION['user_type'])){
    Rights($_SESSION['user_type']);
}else{
    header("Loction :".UrlUsers("home/"));
}

 
$id = $_GET['id'];

$sql = "select * from users where id = $id and user_id <>0" ;
$op  = mysqli_query($con, $sql);
$data = mysqli_fetch_assoc($op);

# Check if id exists
if (mysqli_num_rows($op) == 1) {

    $checkUserAds = selectData("select * from Ads where user_id =$id");

    //// check if user have exists Ads 
    if (mysqli_num_rows($checkUserAds) == 0) {
        $sql = "delete from users where id = $id";
        $op  = mysqli_query($con, $sql);
        if ($op) {
            unlink('./uploads/' . $data['image']);
            $Message = ["Message" => "Raw Removed"];
        } else {
            $Message = ["Message" => "Error try Again"];
        }
    } else {
        $updateUser = "update users set deleted = 1 where user_id=$id";
        $op=mysqli_query($con,$sql);
        if ($op) {
            $Message = ["Message" => "Raw Removed"];
        }else{
            $Message = ["Message" => "Error try Again"];
        }
    }
} else {
    $Message = ["Message" => "Invalid Id "];
}

#   Set Session 
$_SESSION['Message'] = $Message;

header("location: index.php");
