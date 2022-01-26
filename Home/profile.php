<?php
require '../Helpers/dbTransactions.php';
require '../Helpers/functions.php';
///// User data 
$id = $_GET['id'];
$currentUser =    $_SESSION['user_id'];
$current_User_Type = $_SESSION['user_type'];


$data = selectData("select * from users where user_id =$currentUser");
$userProfile = mysqli_fetch_assoc($data);
if ($currentUser != $userProfile['user_id']) {
    header("location:" . UrlUsers("home/index.php"));
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = Clean($_POST['name']);
    $email = Clean($_POST['email']);
    $phone = Clean($_POST['phone']);
    $errors = [];
    if (!Validate($name, 1)) {
        $errors['Name'] = 'Required Field';
    }
    if (!Validate($name, 3, 30)) {
        $errors['Name'] = 'Max Chars 30';
    }
    if (!Validate($email, 1)) {
        $errors['Email'] = 'Required Field';
    }
    if (!Validate($email, 2)) {
        $errors['EmailFormat'] = 'Invalid Email format';
    }
    if (!Validate($phone, 1)) {
        $errors['Phone'] = 'Required Field';
    }
    if (!Validate($phone, 5)) {
        $errors['PhoneFormat'] = 'Invalid Phone format';
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
    } else {
        if (!empty($_FILES['image']['name'])) {
            $disPath = '../uploads/' . $FinalName;

            if (!move_uploaded_file($ImgTempPath, $disPath)) {
                $Message = ['Message' => 'Error  in uploading Image  Try Again '];
            } else {
                unlink('../uploads/' . $userProfile['user_pic']);
            }
        } else {
            $FinalName = $userProfile['user_pic'];
        }

        $sql = "update users set user_name='$name' ,user_email='$email' ,phone='$phone' ,user_pic='$FinalName' where user_id = $currentUser";
        $op = mysqli_query($con, $sql);

        if ($op) {
            $Message = ['Message' => 'Data  Updated'];
        } else {
            $Message = ['Message' => 'Error Try Again ' . mysqli_error($con)];
        }
        # Set Session ......
        $_SESSION['Message'] = $Message;
        header('Location: ' . UrlUsers("home/profile.php?id=$currentUser"));
    }
}


?>

<?php $pageTitle = "Profile";
require '../Design/header.php'; 
require '../Design/navbar.php';
require '../Ads/styleComment.php'
?>


    <main class="container" style="padding-top: 100px;">
        <form action="profile.php?id=<?php echo $id?>"  method="POST" enctype="multipart/form-data">
            

            <div class="card ">
                <div class="row">
        
                    <div class="col-md-3 border-right">
                        <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                            <img class="rounded-circle mt-5" width="150px" src="../uploads/<?php echo $userProfile['user_pic'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6 border-right">
                        <div class="p-3 py-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right">Profile Settings</h4>
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
                            <!--`user_id`, `user_name`, `user_email`, `phone`, `user_password`,
                 `user_type`, `deleted`, `user_pic`-->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label class="labels">Name </label>
                                    <input value="<?php echo $userProfile['user_name'] ?>" type="text" class="form-control" placeholder="enter your name" name="name"></div>
                                <div class="col-md-12"><label class="labels">Email</label><input value=<?php echo $userProfile['user_email'] ?> type="email" class="form-control" placeholder="enter your Email" name="email"></div>
                                <div class="col-md-12"><label class="labels">phone</label><input value=<?php echo $userProfile['phone'] ?> type="text" class="form-control" placeholder="enter your phone" name="phone"></div>
                                <div class="col-md-12"><label class="labels">Picture</label><input type="file" class="form-control" placeholder="Choose" name="image"></div>


                            </div>
                            <br>
                            <a href="change.php">change password</a>

                            <div class="mt-5 text-center"><button class="btn btn-primary profile-button" type="submit">Save
                                    Profile</button></div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </main>

    <?php require '../Design/footer.php'; ?>
