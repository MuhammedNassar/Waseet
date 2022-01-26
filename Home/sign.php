<?php

require '../Helpers/db.php';
require '../Helpers/valid.php';


if (isset($_SESSION['user_id'])) {
    header('location: ' . UrlUsers("home/"));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = clean($_POST['Name']);
    $email = clean($_POST['email']);
    $password = clean($_POST['password']);
    $phone = clean($_POST['phone']);
    $allowed_extension = ['jpg', 'gif', 'png'];
    $errors = [];




    //------- errors ---------------

    //========== name-errors========
    if (validation($name, 1)) {
        $errors['name_require'] = 'Name is required';
    } else {
        if (validation($name, 2)) {
            $errors['name-type'] = 'plese, enter chars only';
        }
    }


    //========== email-errors========
    if (validation($email, 1)) {
        $errors['email_required'] = 'email is required';
    } else {
        if (validation($email, 3)) {
            $errors['email-type'] = 'plese, enter email only';
        }
    }

    //========== password-errors========
    if (validation($password, 1)) {
        $errors['password_require'] = 'password is required';
    } else {
        if (!(validation($password, 4, 7))) {
            $errors['password-len'] = 'password must be >= 8 chars';
        }
    }

    //========== phone-errors========
    if (validation($phone, 1)) {
        $errors['phone_require'] = 'phone is required';
    } else {
        if ((validation($phone, 5))) {
            $errors['phone-type'] = 'enter correct number';
        }
    }


    //========== image-errors======== 
    if (empty($_FILES['image']['name'])) {
        $errors['image_require'] = 'your image is required';
    } else {
        $image = $_FILES['image']['name'];
        $temppath = $_FILES['image']['tmp_name'];
        $extension = explode('.', $image);
        $imgextension = strtolower(end($extension));
        if (in_array($imgextension, $allowed_extension)) {
            $final = rand() . time() . '.' . $imgextension;
            $path = '.././uploads/' . $final;
            if (!move_uploaded_file($temppath, $path)) {
                $errors['tyr'] = 'uploading error try again';
            }
        } else {
            $errors['imageextension'] = 'extension not allowed';
        }
    }



    if (!(count($errors) > 0)) {
        $password = md5($password);
        $data_user = " insert into users (user_name , user_email , phone , user_password , user_pic , user_type) 
        values ( '$name' , '$email' , '$phone' , '$password' , '$final' , 3)";
        $op_user = mysqli_query($con, $data_user);
        if ($op_user) {
            $_SESSION['Done'] = 'Thanks for join us';
            header('location: login.php ');
        } else {
            echo 'error' . mysqli_error($con);
        }
    }
}



?>
<?php require '../Design/header.php';
require '../Design/navbar.php';
require '../Ads/styleComment.php';
?>

<div class="container" style="padding-top: 100px;">
    <div class="card">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-12 d-none d-lg-block bg-register-image"></div>
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                        </div>
                        <?php
                        if (isset($errors)) {
                            if (count($errors) > 0) { ?>

                                <div style="margin-bottom:15px; background:#ddd; padding:10px; ">
                                    <?php
                                    foreach ($errors as $key => $val) {

                                        echo '<label style="color:#600; " >' . $val  . '</label>' . '<br>';
                                    } ?>

                                </div>


                        <?php

                            }
                        }
                        ?>


                        <form class="user" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method='post' enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="exampleInputName">Name</label>
                                <input type="text" class="form-control form-control-user" id="exampleInputEmail" name="Name" placeholder="Enter your name">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputName">Email</label>
                                <input type="text" class="form-control form-control-user" id="exampleInputEmail" name="email" placeholder="Email Address">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputName">Password</label>
                                <input type="password" class="form-control form-control-user" id="exampleInputEmail" name="password" placeholder="Enter your password">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputName">Phone</label>
                                <input type="text" class="form-control form-control-user" id="exampleInputEmail" name="phone" placeholder="Phone number">
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">image</label>
                                <input type="file" class="form-control" id="exampleFormControlInput1" name='image'>
                            </div>
                            <hr>
                            <button class="btn btn-primary btn-user btn-block">
                                Register Account
                            </button>

                        </form>

                        <div class="text-center">
                            <a class="small" href="login.php">Already have an account? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require '../Design/footer.php'; ?>