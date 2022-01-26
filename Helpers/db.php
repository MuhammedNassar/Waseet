<?php

if (session_status()!== PHP_SESSION_ACTIVE) {
     session_start();
}  

$server="localhost";
$dbname="e3lan"; 
$dbuser="root";
$dbpsw="";
$con = mysqli_connect($server,$dbuser,$dbpsw,$dbname);
if (!$con) {
    die('Error') . mysqli_connect_error($con) ;
}
?>