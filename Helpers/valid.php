<?php

function clean ( $var ){
    $var = trim(strip_tags($var));
    return $var;
}

function validation($check , $flag,$input=null){
    $result = false;
    switch ($flag){
            
      //------ empty or not-----        
      case 1:
        if(empty($check)){ 
            $result = true;  
        } break;
            
      //------ string type-----        
      case 2:
        if(!preg_match('/^[a-zA-Z\s]*$/' , $check) ){
            $result = true;  
        } break;   
            
     //------ email type-----        
      case 3:
        if(!(filter_var($check , FILTER_VALIDATE_EMAIL))){
            $result = true;  
        } break;                
          
     //------ password length-----        
      case 4:
        if( strlen($check) > $input ){
            $result = true;  
        } break;
            
     //------ phone type-----        
      case 5:
        if(!preg_match('/^01[0-2,5][0-9]{8}$/' , $check) ){
            $result = true;  
        } break; 
        
        case 6:
         #code .... 
         if (!preg_match('/^[a-zA-Z\s]*$/', $check)) {
            $result = true;
         }
         break;


      case 7:
         # Code ....
         $allowedExt = ['png', 'jpg', 'jpeg'];
         if (!in_array($check, $allowedExt)) {
            $result = true;
         }
         break;
      case 8:
         if (!isset($check)) {
            $result = true;
         }
         break;
      case 8:
         if (!preg_match("^[a-zA-Z0-9!@#$&()\\-`.+,/\"]*$", $check)) {
            $result = true;
         }
         break;
      case 9:
         if (!preg_match('/^[0-9]/', $check)) {
            $result = true;
         }    break;
             
            
            
            
            
        }    
    
    return $result;

}

  function Messages($Message){
    foreach ($Message as $key => $value) {
            # code...
            echo '* ' . $key . ' : ' . $value . '<br>';
            
        }

   }



function UrlUsers($strPath)
{

   return 'http://' . $_SERVER['HTTP_HOST'] . "/Waseet" . "/" . $strPath;
}
function Url($url = null){

     return   'http://'.$_SERVER['HTTP_HOST'].'/waseet/admin/'.$url;

   }

   function Rights($strUser){     
    if(!empty($strUser)){
      if (!($strUser == '1')) {
        if (!empty($strUser)) {
         header("Location: /Waseet/Home/index.php");
        }else{
         header("Location: /Waseet/Home/login.php");
        }
     }
 
    }else{
      header("Location: /Waseet/Home/login.php");
    }
   }
?>