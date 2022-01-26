<?php

require '../Helpers/dbTransactions.php';
require '../Helpers/valid.php';
$currentUser =    $_SESSION['user_id'];
$current_User_Type = $_SESSION['user_type'];
$ad_id = $_GET['id'];
///////////////////


$ad_title;
$ad_body;
$ad_date;
$ad_section;
$price;
$user_name;
$user_email;
$user_id;
$phone;
$imgUrl;
$is_sold;
$imgUrl;
$total_comments;
////////////////////////Fetch Ad Details ////////////////////////////////////
if (isset($ad_id)) {

   $data = selectData("select  ads.ad_id , ads.ad_title, ads.ad_body,ads.ad_date,sections.section_name,ads.price,
       users.user_id,users.user_name,users.user_email,user_pic,users.phone,ads.ad_pic,ads.is_sold
           from ads  INNER join users  on ads.user_id = users.user_id 
                   INNER join sections on sections.section_id = ads.section_id
                       where users.deleted <> 1  and ads.deleted <> 1 and ads.ad_id=$ad_id");
   $adData = mysqli_fetch_assoc($data);
   if (mysqli_num_rows($data) != 0) {
      $user_id = $adData['user_id'];
      $user_name = $adData['user_name'];
      $user_email = $adData['user_email'];
      $ad_title = $adData['ad_title'];
      $ad_body = $adData['ad_body'];
      $ad_date = $adData['ad_date'];
      $ad_section = $adData['section_name'];
      $price = $adData['price'];
      $phone = $adData['phone'];
      $imgUrl = $adData['ad_pic'];
      $is_sold = $adData['is_sold'];
      $user_pic = $adData['user_pic'];
      ///////////////////////COUNT COMMENTS/////////////////////////////////
      $Sql = selectData("select comments.comment_id as id FROM  comments  where comments.ad_id=$ad_id
                       union all 
                           SELECT sub_comments.comment_id as id FROM  sub_comments
                              inner join comments on sub_comments.comment_id = comments.comment_id where comments.ad_id=$ad_id;");
      $total_comments = mysqli_num_rows($Sql);
      /////////////////////Check if item sold /////////////////////////////
      if ($is_sold) {
         $ad_title = $ad_title . '  <span style="color: red; "> ( SOLD OUT )</span> ';
         $phone = "Not Available";
         $price = "";
      }
   } else {
      header("Location: " . UrlUsers("Home/index.php"));
   }
} else {
   header("Location: " . UrlUsers("Home/index.php"));
}

function InsertComments($input, $comment, $id = null)
{
   global $ad_id;
   global $currentUser;
   if ($input == "C") {
      insertData("insert into sub_comments 
                  (comment_id,sub_comment_text,sub_comment_date,user_id) 
                    Values
                     ($id,'$comment','" . date("Y-m-d H:i:s") . "','$currentUser')");
   } else {
      insertData("insert into comments
                  (comment_text,comment_date,ad_id,user_id) 
                   Values 
                      ('$comment','" . date("Y-m-d H:i:s") . "',$ad_id,$currentUser)");
   }
}
