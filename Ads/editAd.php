<?php
require '../Helpers/dbTransactions.php';
require '../Helpers/functions.php';
///// Ad data 
$currentUser =    $_SESSION['user_id'];
$current_User_Type = $_SESSION['user_type'];

$ad_id = $_GET['id'];
$check = selectData("select ads.*,users.user_type from ads inner join users on ads.user_id = users.user_id  where ad_id =$ad_id");
$data= mysqli_fetch_assoc($check);
$ss = "SELECT `section_id`, `section_name`, `is_active` FROM `sections` WHERE is_active=1";
$section = selectData($ss);

if(!($data['user_id']==$currentUser || 3 !=$current_User_Type))
{
    header("Location: " . UrlUsers("Home/index.php"));
    exit();
   
}


////////////////////////Fetch Ad Details ////////////////////////////////////
if (isset($ad_id)) {

    $data = selectData("select  ads.ad_id , ads.ad_title, ads.ad_body,ads.ad_date,sections.section_id,sections.section_name,ads.price,
    users.user_id,users.user_name,users.user_email,users.phone,ads.ad_pic,ads.is_sold
        from ads  INNER join users  on ads.user_id = users.user_id 
                INNER join sections on sections.section_id = ads.section_id
                    where users.deleted <> 1  and ads.deleted <> 1 and ads.ad_id=$ad_id");
    $adData = mysqli_fetch_assoc($data);
} else {
    header("Location: " . UrlUsers("Home/index.php"));
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $title = Clean($_POST['title']);
    $body = Clean($_POST['body']);
    $date = Clean($_POST['date']);
    $section = Clean($_POST['section_id']);
    $price = Clean($_POST['price']);

    $is_sold = $adData['is_sold'];
    $Is_sold_db = 0;

    if (isset($_POST['is_sold'])) {
        $Is_sold_db = 1;
    }
    # Validate Title ....
    $errors = [];
    if (!Validate($title, 1)) {
        $errors['Title'] = 'Required Field';
    } elseif (!Validate($title, 6)) {
        $errors['Title'] = 'Invalid String';
    }
    if (!Validate($title, 3, 100)) {
        $errors['LongTitle'] = 'Title most have at most 100 chars';
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
        $errors['priceNulls'] = 'Required Field';
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
        # Set Session ...... 
           
        $_SESSION['Message'] = $errors;
       exit();
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
        $sql = "update ads set ad_title='$title' ,ad_body = '$body',ad_date='$date',section_id=$section,price=$price,is_sold=$Is_sold_db,deleted=0,user_id=$currentUser,ad_pic='$FinalName' where ad_id = $ad_id";
        $op = mysqli_query($con, $sql);

        if (!$op) {
            $Message = ['Message' => 'Error Try Again ' . mysqli_error($con)];
        } else{
            $Message = ['Message' => 'Edited Successfully'];
        }

        # Set Session ......
       
        $_SESSION['Message'] = $Message;

        header('Location: ' . UrlUsers("ads/ad-details.php?id=$ad_id"));
        
       
    }
}





?>


<?php $pageTitle = "Edit Ad Details";
require '../Design/header.php'; 
require '../Design/navbar.php';
require '../Ads/styleComment.php';
?>
<style>
    .form-group{
        padding-right: 20px;
        padding-left: 20px;
    }
</style>


<section style="background-color: #dee1e3;">

    <div class="container"  style="padding-top: 100px;">

        <!-- `title`, `ad_body`, `ad_date`, `section_id`, `price`, `is_sold`, `deleted`, `user_id`, `ad_pic`-->

        <div class="row">
            <form action="editAd.php?id=<?php echo $ad_id; ?>" method="POST" enctype="multipart/form-data">
                <div class="col-md-12">
                    <div class="container col-md-11">
                        <div class="card" style="border-radius: 11px;">
                            <div class="form-group">

                                <?php
                    echo '<br>';
                    if (isset($_SESSION['Message'])) {
                        Messages($_SESSION['Message']);
                        # Unset Session ...
                        unset($_SESSION['Message']);
                    }
                    ?>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="<?php echo $adData['ad_title'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1" class="form-label">Ad Desc</label>
                                <textarea class="form-control" id="body" name="body"
                                    rows="5"><?php echo $adData['ad_body'] ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Date</label>
                                <input type="date" class="form-control" value="<?php echo $adData['ad_date'] ?>"
                                    id="date" name="date">
                            </div>
                            <div class="form-group">
                                <select name="section_id" class="form-select" aria-label="Default select example">
                                    <?php
                            while ($sec = mysqli_fetch_assoc($section)) {
                            ?>
                                    <option value="<?php echo $sec["section_id"] ?> " <?php
                                                                                    if ($sec['section_id'] == $adData['section_id']) {
                                                                                        echo "selected";
                                                                                    } ?>>
                                        <?php echo $sec["section_name"] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Price</label>
                                <input type="text" class="form-control" value="<?php echo $adData['price'] ?>"
                                    id="price" name="price">
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" <?php if ($adData['is_sold'] == 1) {
                                                                                echo "checked";
                                                                            }
                                                                            ?> id="Check" name="is_sold">
                                    <label class="form-check-label" for="defaultCheck1">
                                        IS SOLD
                                    </label>
                                </div>
                            </div>


                            <div class="form-group" style="height: 200px;width:200px">
                                <img src="../uploads/<?php echo $adData["ad_pic"] ?>" alt="" class="img-fluid">
                            </div>

                            <div class="form-group">
                                <label for="formFile" class="form-label">Choose a Picture </label>
                                <input class="form-control" type="file" id="image" name="image">
                            </div>


                            <br>
                            <div class="form-group">
                            <input class="btn btn-dark" type="submit" value="Save">
                            </div>

                            <br>

                        </div>
                    </div>
                </div>
            </form>

        </div>


    </div>
</section>
<?php require '../Design/footer.php'; ?>