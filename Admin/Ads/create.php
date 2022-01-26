<?php
require '../../helpers/dbTransactions.php';
require '../../helpers/functions.php';


# Fetch Roles ....

$currentUser= $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $title =Clean($_POST['title']);
    $body =Clean($_POST['body']);
    $date = Clean($_POST['date']);
    $section_id =Clean($_POST['section_id']);
    $price =Clean($_POST['price']); 
    $sold = 0 ;
    $deleted = 0 ;
    
    if(isset($_POST['is_sold'])){   
    $sold =Clean($_POST['is_sold']); 
    }     
    if(isset($_POST['deleted'])){   
    $deleted = Clean($_POST['deleted']); 
    }     
    # !Validate Title ....
    $errors = [];   
    if (!Validate($title, 1)) {
        $errors['Title'] = 'Required Field';
    } elseif (!Validate($title, 6 )) {
        $errors['Title'] = 'Invalid String';
    }
    if (!Validate($title, 4,100)) {
        $errors['LongTitle'] = 'Title must have at most 100 chars';
    }
    if (!Validate($body, 1)) {
        $errors['ad_body'] = 'Required Field';
    } elseif (!Validate($body, 8)) {
        $errors['ad_body'] = 'Invalid String';
    }    
    if (!Validate($date, 8)) {
        $errors['Date'] = 'Required Field';
    }
    if (!Validate($price, 1)) {
        $errors['price'] = 'Required Field';
    }if (!Validate($price, 9)) {
        $errors['price'] = 'Required numbers';
    }  
    if (!empty($_FILES['image']['name'])){
        $ImgTempPath = $_FILES['image']['tmp_name'];
        $ImgName = $_FILES['image']['name'];
        $extArray = explode('.', $ImgName);
        $ImageExtension = strtolower(end($extArray));

        if (!Validate($ImageExtension, 7)) {
            $errors['Image'] = 'Invalid Extension';
        } else {
            $FinalName = time() . rand() . '.' . $ImageExtension;
        }
    }else{
           $errors['Image_r'] = 'Image is required';
    }
    if (count($errors) > 0) {
        # Set Session ......
        $_SESSION['Message'] = $errors;
       
    } else {        
        if (!empty($_FILES['image']['name'])) {
            $disPath = '../../uploads/' . $FinalName;

            if (!move_uploaded_file($ImgTempPath, $disPath)) {
                $Message = ['Message' => 'Error  in uploading Image  Try Again '];
            } 
        } 
//,`section_id`='[value-5]',`price`='',`is_sold`='[]',`deleted`='[]',`user_id`='[]',`ad_pic`='[]' WHERE 1
      insertData("insert INTO ads(ad_title, ad_body, ad_date, 
                    section_id, price, is_sold, deleted,user_id, ad_pic) 
             VALUES   ('$title','$body','$date',$section_id,$price,$sold,$deleted,$currentUser,'$FinalName')");
             $lastRow = mysqli_insert_id($con);
        if ($op) {
            $Message = ['Message' => 'Raw Updated'];          
        } 
        else {
            $Message = ['Message' =>  mysqli_error($con)];      
        }
        # Set Session ......
        $_SESSION['Message'] = $Message;       
    }
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
            <li class="breadcrumb-item active">Dashboard/Articales/Create</li>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </ol>
        <div class="card mb-4">
         <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputName">Title</label>
                        <input type="text" class="form-control" id="exampleInputName" name="title" aria-describedby=""
                            placeholder="Enter Title">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName">Ad Desc</label>
                        <textarea class="form-control" id="exampleInputName" name="body"></textarea>
                    </div>                             
                    <div class="form-group">
                        <label for="exampleInputName">Date</label>
                        <input type="date" class="form-control" id="exampleInputName" name="date" aria-describedby="">
                    </div>                                    
                <div class="mb-3">
                <label for="exampleInputPassword">Category</label>
                <select name="section_id" class="form-select" aria-label="Default select example">
                        <?php
                     
                      $section = selectData('select * from sections');
                  
                            while ($sec = mysqli_fetch_assoc($section)) {
                            ?>
                        <option value="<?php echo $sec['section_id']; ?> ">
                            <?php echo $sec["section_name"] ;?></option>
                        <?php } ?>                    
                </div>                                
               <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Price</label>
                    <input type="text" class="form-control" id="price" name="price">
                </div>                    
                 <div class="mb-3">
                    <div class="form-check">
                        <input value ='<?php echo 1; ?>' class="form-check-input" type="checkbox" id="Check" name="is_sold">
                        <label class="form-check-label" for="defaultCheck1">
                            IS SOLD
                        </label>
                    </div>
                </div>                                
            <div class="mb-3">
                    <div class="form-check">
                        <input value ='<?php echo 1; ?>' class="form-check-input" type="checkbox" id="Check" name="deleted">
                        <label class="form-check-label" for="defaultCheck1">
                            Deleted
                        </label>
                    </div>
                </div>                                              
                <div class="mb-3">
                    <label for="formFile" class="form-label">Choose a Picture </label>
                    <input class="form-control" type="file" id="image" name="image">
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
