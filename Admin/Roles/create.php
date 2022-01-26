<?php
require '../../helpers/db.php';
require '../../helpers/valid.php';


# Code ..... 

if($_SERVER['REQUEST_METHOD'] == "POST"){

   $type = Clean($_POST['type']);

   # Validate type .... 
   $errors = [];

   if(validation($type,1)){
      $errors['type'] = "Required Field";
   }elseif(validation($type,2)){
      $errors['$type'] = "Invalid String"; 
   }


     if(count($errors) > 0){
          $Message = $errors;
     }else{
        // DB CODE ..... 
        $sql = "insert into users_types (user_type_name) values ('$type')";
        $op  = mysqli_query($con,$sql);

        if($op){
            $Message = ["Message" => "Raw Inserted"];
        }else{
            $Message = ["Message" => "Error Try Again ".mysqli_error($con)];
        }

     }
       # Set Session ...... 
       $_SESSION['Message'] = $Message;

}






require '../layouts/header.php';
require '../layouts/nav.php';
require '../layouts/sidNav.php';
?>



<main>
    <div class="container-fluid">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard/Roles/Create</li>

           <?php 
               echo '<br>';
              if(isset($_SESSION['Message'])){
                Messages($_SESSION['Message']);
             
                 # Unset Session ... 
                 unset($_SESSION['Message']);
            }
           
           ?>

        </ol>


        <div class="card mb-4">

            <div class="card-body">

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

                    <div class="form-group">
                        <label for="exampleInputName">Type</label>
                        <input type="text" class="form-control" id="exampleInputName" name="type" aria-describedby=""
                            placeholder="Enter new type">
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>





            </div>
        </div>
    </div>
</main>


<?php
require '../layouts/footer.php';
?>
