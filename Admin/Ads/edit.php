<?php
require '../../helpers/db.php';
require '../../helpers/functions.php';



$id = $_GET['id'];
$user_id = $_SESSION['user_id'];



# Fetch Roles ....

$sqlAds = "select * from ads where ad_id =$id";
$adsOp = mysqli_query($con, $sqlAds);
$ads = mysqli_fetch_assoc($adsOp);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {



    $title = Clean($_POST['title']);
    $body = Clean($_POST['body']);
    $date = $_POST['date'];
    $section = Clean($_POST['section_id']);
    $price = Clean($_POST['price']);
    $sold = 0;
    $deleted = 0;

    if (isset($_POST['is_sold'])) {
        $sold = Clean($_POST['is_sold']);
    }

    if (isset($_POST['deleted'])) {
        $deleted = Clean($_POST['deleted']);
    }

    # Validate Title ....
    $errors = [];


    if (!Validate($title, 1)) {
        $errors['Title'] = 'Required Field';
    } elseif (!Validate($title, 6)) {
        $errors['Title'] = 'Invalid String';
    }
    if (!Validate($date, 1)) {
        $errors['date'] = 'Required Field';
    }
    if (!Validate($title, 3, 50)) {
        $errors['LongTitle'] = 'Title must have at most 50 chars';
    }
    if (!Validate($body, 1)) {
        $errors['ad_body'] = 'Required Field';
    }
   
    if (!Validate($price, 1)) {
        $errors['price'] = 'Required Field';
    }
    if (!Validate($price, 9)) {
        $errors['price'] = 'Required numbers';
    }



    if (!empty($_FILES['image']['name'])) {
        $ImgTempPath = $_FILES['image']['tmp_name'];
        $ImgName = $_FILES['image']['name'];

        $extArray = explode('.', $ImgName);
        $ImageExtension = strtolower(end($extArray));

        if (!Validate($ImageExtension, 7)) {
            $errors['Image'] = 'Invalid Extension';
        } else {
            $FinalName = time() . rand() . '.' . $ImageExtension;
        }
    } 


    if (count($errors) > 0) {
        $Message = $errors;
    } else {
        // DB CODE .....

        if (!empty($_FILES['image']['name'])) {
            $disPath = '../../uploads/' . $FinalName;

            if (!move_uploaded_file($ImgTempPath, $disPath)) {
                $Message = ['Message' => 'Error  in uploading Image  Try Again '];
            } else {
                unlink('../../uploads/' . $ads['ad_pic']);
            }
        } else {
            $FinalName = $ads['ad_pic'];
        }

        if (count($errors) == 0) {
     
            $sql = "update ads set ad_title='$title' , ad_body='$body' , ad_date= '$date' ,
            section_id = $section , price =$price , is_sold =$sold , deleted = $deleted , 
            user_id = $user_id ,  ad_pic = '$FinalName'  where ad_id = $id";


            $op = mysqli_query($con, $sql);

            if ($op) {
                $Message = ['Message' => 'Raw Updated'];
            } else {
                $Message = ['Message' => 'Error Try Again ' . mysqli_error($con)];
            }
        }

        $_SESSION['Message'] = $Message;
        header('Location: index.php');
        exit();
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

            <?php
            echo '<br>';
            if (isset($_SESSION['Message'])) {

                foreach ($_SESSION['Message'] as $key => $val) {
                    echo '<br>'.$key.' '. $val;
                }

                unset($_SESSION['Message']);
            }

            ?>

        </ol>


        <div class="card mb-4">

            <div class="card-body">

                <form action="<?php echo $_SERVER["PHP_SELF"] . '?id=' . $id ?>" method="post"
                    enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="exampleInputName">Title</label>
                        <input type="text" class="form-control" id="exampleInputName" name="title" aria-describedby=""
                            value='<?php echo $ads['ad_title']; ?>' placeholder="Enter Title">
                    </div>


                    <div class="form-group">
                        <label for="exampleInputName">Ad Desc</label>
                        <textarea class="form-control" id="exampleInputName"
                            name="body"> <?php echo $ads['ad_body']; ?> </textarea>
                    </div>



                    <div class="form-group">
                        <label for="exampleInputName">Date</label>
                        <input type="date" class="form-control" id="exampleInputName" name="date" aria-describedby=""
                            value='<?php echo $ads['ad_date']; ?>'>
                    </div>


                    <div class="form-group">
                    <label for="exampleInputPassword">Category</label>   
                    <select name="section_id" class="form-select" id="section_id">
                            <?php
                            $sql = "select * from sections ";
                            $section = mysqli_query($con, $sql);
                            while ($sec = mysqli_fetch_assoc($section)) {
                            ?>
                            <option value="<?php echo $sec["section_id"]; ?>" <?php if ($sec['section_id'] == $ads['section_id']) {
                                                                                        echo 'selected';
                                                                                    } ?>>
                                <?php echo $sec['section_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="form-label">Price</label>
                        <input type="text" class="form-control" id="price" name="price"
                            value='<?php echo $ads['price']; ?>'>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input value='<?php echo 1; ?>' class="form-check-input" type="checkbox" id="Check"
                                name="is_sold">
                            <label class="form-check-label" for="defaultCheck1" <?php if ($ads['is_sold'] == 1) {
                                                                                    echo "checked";
                                                                                } ?>>
                                IS SOLD
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input value='<?php echo 1; ?>' class="form-check-input" type="checkbox" id="Check"
                                name="deleted"
                                <?php if ($ads['deleted'] == 1) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?>>
                            <label class="form-check-label" for="defaultCheck1">
                                Deleted
                            </label>
                        </div>
                    </div>
                    <div class="form-group" style="height: 200px;width:200px">
                        <img src="../../uploads/<?php echo $ads["ad_pic"] ?>" alt="" class="img-fluid">
                    </div>

                    <div class="form-group">
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