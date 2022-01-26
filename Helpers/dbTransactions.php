<?php
require 'db.php';//database connect
$errors=[]; 

function insertData($strQuery) // pass query as param then method return inserted or error
{ 
    global $con;
    $Messege='';
    $excute= mysqli_query($con,$strQuery);
    if (!$excute) {
          $Messege= 'Error happend while insert data '.mysqli_error($con);
    }else{
           $Messege = 'Data inserted ';
    }
    return $Messege;
}


function updateData($strQuery) // pass query as param then method return Updated or error
{
    global $con;
    $Messege='';
    $excute= mysqli_query($con,$strQuery);
    if (! $excute) {
        $Messege= 'Error happend while updating data '.mysqli_error($con);
    }else{
        $Messege = 'Data Updated ';
    }
    return $Messege;
}
  
function selectData($strQuery){
    global $con;
   $sql = $strQuery;
   $data=mysqli_query($con,$sql);
return $data;
}
?>