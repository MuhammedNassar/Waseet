<?php 

function Clean($input){

     return  trim(strip_tags(stripslashes($input)));
}


  function Validate($input,$flag,$length =null){

    $status = true;

     switch ($flag) {
         case 1:
             # code...
             if (empty($input)) {
                $status = false;
             }
             break;
      
        case 2: 
         # code .... 
         if (!filter_var($input, FILTER_VALIDATE_EMAIL)){
            $status = false;
         } 
          break;


        case 3: 
           #code .... 6           30
           if (!strlen($input) > $length){
               $status = false;
           }  
           break;
 

        case 4: 
         # code .... 
         if (!filter_var($input, FILTER_VALIDATE_INT)){
            $status = false;
         } 
          break;    



          case 5: 
           #code .... 
           if(!preg_match('/^01[0-2,5][0-9]{8}$/',$input)){
               $status = false;
           }  
           break;  




           case 6: 
              #code .... 
              if(!preg_match('/^[a-zA-Z\s]*$/',$input)){
                 $status = false;
              }
              break;


            case 7: 
            # Code ....
            $allowedExt = ['png','jpg']; 
                 if(!in_array($input,$allowedExt)){
                    $status = false;
                 }
            break;
           
            case 8:
               if (!isset($input)) {
                  $status = false;
               }
               break;
            case 8:
               if (!preg_match("^[a-zA-Z0-9!@#$&()\\-`.+,/\"]*$", $input)) {
                  $status = false;
               }
               break;
            case 9:
               if (!preg_match('/^[0-9]/', $input)) {
                  $status = false;
               }
     }

     return $status;

  }



   function Messages($Message){
    foreach ($Message as $key => $value) {
            # code...
            echo '* ' . $key . ' : ' . $value . '<br>';
            
        }

   }

   function Url($url = null){

     return   'http://'.$_SERVER['HTTP_HOST'].'/Waseet/admin/'.$url;

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
 
 
function UrlUsers($strPath)
{

   return 'http://' . $_SERVER['HTTP_HOST'] . "/Waseet" . "/" . $strPath;
}
function UrlAdmins($strPath)
{

   return 'http://' . $_SERVER['HTTP_HOST'] . '/Waseet/Admin/' . $strPath;
}


?>