<?php

require '../Helpers/dbTransactions.php';
require '../Helpers/valid.php';

$currentUser =    $_SESSION['user_id'];
$current_User_Type = $_SESSION['user_type'];
$sql = "select * from sections";
$section  = mysqli_query($con, $sql);








if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $title = Clean($_POST['title']);
    $body = Clean($_POST['body']);
    $date = Clean($_POST['date']);
    $section = Clean($_POST['section_id']);
    $price = Clean($_POST['price']);


    # Validate Title ....
    $errors = [];
    if (validation($title, 1)) {
        $errors['Title'] = 'Required Field';
    } elseif (validation($title, 6)) {
        $errors['Title'] = 'Invalid String';
    }
    if (validation($title, 4, 100)) {
        $errors['LongTitle'] = 'Title must have at most 100 chars';
    }
    if (validation($body, 1)) {
        $errors['ad_body'] = 'Required Field';
    } elseif (validation($body, 8)) {
        $errors['ad_body'] = 'Invalid String';
    }

    if (validation($date, 8)) {
        $errors['Date'] = 'Required Field';
    }
    if (validation($price, 1)) {
        $errors['price'] = 'Required Field';
    }
    if (validation($price, 9)) {
        $errors['price'] = 'Required numbers';
    }
    if (!empty($_FILES['image']['name'])) {
        $ImgTempPath = $_FILES['image']['tmp_name'];
        $ImgName = $_FILES['image']['name'];

        $extArray = explode('.', $ImgName);
        $ImageExtension = strtolower(end($extArray));

        if (validation($ImageExtension, 7)) {
            $errors['Image'] = 'Invalid Extension';
        } else {
            $FinalName = time() . rand() . '.' . $ImageExtension;
        }
    }


    if (count($errors) > 0) {
        # Set Session ......
        $_SESSION['Message'] = $errors;
    } else {

        if (!empty($_FILES['image']['name'])) {
            $disPath = '../uploads/' . $FinalName;

            if (!move_uploaded_file($ImgTempPath, $disPath)) {
                $Message = ['Message' => 'Error  in uploading Image  Try Again '];
            } else {
                unlink('../uploads/' . $adData['ad_pic']);
            }
        } else {
            $FinalName = $adData['ad_pic'];
        }
        //,`section_id`='[value-5]',`price`='',`is_sold`='[]',`deleted`='[]',`user_id`='[]',`ad_pic`='[]' WHERE 1
        insertData("insert INTO ads(ad_title, ad_body, ad_date, 
                    section_id, price, is_sold, deleted,user_id, ad_pic) 
             VALUES   ('$title','$body','$date',$section,$price,0,0,$currentUser,'$FinalName')");
        $lastRow = mysqli_insert_id($con);
        if ($op) {
            $Message = ['Message' => 'Raw Updated'];
        } else {
            $Message = ['Message' => 'Error Try Again ' . mysqli_error($con)];
        }

        # Set Session ......
        $_SESSION['Message'] = $Message;


        header('Location: ' . UrlUsers("ads/ad-details.php?id=$lastRow"));
    }
}





?>

<?php $pageTitle = "Create Ad";
require '../Design/header.php';
require '../Design/navbar.php';
require '../Ads/styleComment.php';
?>



    <div class="container ">


        <div class="row center"  style="padding-top: 100px;" >

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                <div class="card">


                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1" class="form-label">Ad Desc</label>
                        <textarea class="form-control" id="body" name="body" rows="5"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date">
                    </div>


                    <div class="form-group">
                        <select name="section_id" class="form-select" aria-label="Default select example">
                            <?php
                            while ($sec = mysqli_fetch_assoc($section)) {
                            ?>
                                <option value="<?php echo $sec["section_id"] ?> ">
                                    <?php echo $sec["section_name"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="form-label">Price</label>
                        <input type="text" class="form-control" id="price" name="price">
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="Check" name="is_sold">
                            <label class="form-check-label" for="defaultCheck1">
                                IS SOLD
                            </label>
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="formFile" class="form-label">Choose a Picture </label>
                        <input class="form-control" type="file" id="image" name="image">
                    </div>
                <div>
                    <?php
                    echo '<br>';
                    if (isset($_SESSION['Message'])) {
                        Messages($_SESSION['Message']);
                        # Unset Session ...
                        unset($_SESSION['Message']);
                    }
                    ?>
                </div>

                <div class="form-field col-lg-12">
                    <button class="btn btn-dark" type="submit" value="Submit"> Save</button>
                </div>


            </form>
        </div>
    </div>

    <?php require '../Design/footer.php'; ?>
