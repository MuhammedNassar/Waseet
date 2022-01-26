<?php
require './Helpers/valid.php'; 
function CheckLogIn(){
  if (!isset($_SESSION['user_id'])) {
    
    header("Location: ".UrlUsers("home/login.php"));
          }
 }

?>