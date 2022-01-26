<?php
require '../../helpers/db.php';
require '../../helpers/functions.php';
if(isset($_SESSION['user_type'])){
    Rights($_SESSION['user_type']);
}else{
    header("Loction :".UrlUsers("home/"));
}


$id = $_GET['id'];
$sql = "select * from users where user_id = $id and user_id <>0";
$usersOp = mysqli_query($con, $sql);

if (mysqli_num_rows($usersOp) == 1) {
    $UserData = mysqli_fetch_assoc($usersOp);
} else {
    $_SESSION['Message'] = ['Message' => 'Invalid Id'];
    header('Location: index.php');
}
// types
$sql = 'select * from users_types';
$types = mysqli_query($con, $sql);



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name      = Clean($_POST['name']);
    $email     = Clean($_POST['email']);
    //$password  = Clean($_POST['password']);
    $user_type   = $_POST['user_type'];
    $phone     = Clean($_POST['phone']);

    // Validate name ....
    $errors = [];

    if (!Validate($name, 1)) {
        $errors['Name'] = 'Required Field';
    } elseif (!Validate($name, 6)) {
        $errors['Name'] = 'Invalid String';
    }

    // Validate mail
    if (!Validate($email, 1)) {
        $errors['Email'] = 'Field Required';
    } elseif (!Validate($email, 2)) {
        $errors['Email'] = 'Invalid Email';
    }
    // Validate Types .... 
    if (!Validate($user_type, 1)) {
        $errors['Type'] = 'Field Required';
    } elseif (!Validate($user_type, 4)) {
        $errors['Type'] = "Invalid Type Id";
    }
    // Validate phone .... 
    if (!Validate($phone, 1)) {
        $errors['Phone'] = 'Field Required';
    } elseif (!Validate($phone, 5)) {
        $errors['phone'] = 'InValid Number';
    }
    // Validate Image
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
        if (!empty($_FILES['image']['name'])) {
            $disPath = '../../uploads/' . $FinalName;

            if (!move_uploaded_file($ImgTempPath, $disPath)) {
                $Message = ['Message' => 'Error  in uploading Image  Try Again '];
            } else {
                unlink('../../uploads/' . $UserData['user_pic']);
            }
        } else {
            $FinalName = $UserData['user_pic'];
        }
        $sql = "update users set user_name='$name' ,user_email='$email' ,phone='$phone' ,user_type=$user_type,user_pic='$FinalName' where user_id = $id";
        $op = mysqli_query($con, $sql);
        if ($op) {
            $Message = ['Message' => 'Data  Updated'];
        } else {
            $Message = ['Message' => 'Error Try Again ' . mysqli_error($con)];
        }
        $_SESSION['Message'] = $Message;
        header('Location: index.php');
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
            <li class="breadcrumb-item active">Dashboard/Users/Edit</li>

            <?php
            echo '<br>';
            if (isset($_SESSION['Message'])) {
                Messages($_SESSION['Message']);

                # Unset Session ...
                unset($_SESSION['Message']);
            }

            ?>

        </ol>


        <div class="card mb-4">

            <div class="card-body">

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?id=$id"; ?>" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="exampleInputName">Name</label>
                        <input type="text" value="<?php echo $UserData['user_name'] ?>" class="form-control" id="exampleInputName" name="name" aria-describedby="" placeholder="Enter Name">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail">Email address</label>
                        <input type="text" value="<?php echo $UserData['user_email'] ?>" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Enter email">
                    </div>
                    <!-----  PASSWORD
                    <div class="form-group">
                        <label for="exampleInputPassword">New Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
                    </div>
                      -->

                    <div class="form-group">
                        <label for="exampleInputPassword">Type</label>
                        <select class="form-control" id="exampleInputPassword1" name="user_type">
                            <?php
                            while ($data = mysqli_fetch_assoc($types)) {
                            ?>
                                <option value="<?php echo $data['user_type']; ?>"> <?php echo $data['user_type_name']; ?>
                                </option>
                            <?php }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputName">Phone</label>
                        <input type="text" value="<?php echo $UserData['phone']; ?>" class="form-control" id="exampleInputName" name="phone" aria-describedby="" placeholder="Enter phone">
                    </div>
                    <div class="form-group">

                        <img src="/waseet/uploads/<?php echo $UserData['user_pic']; ?>" height="100px" width="100px">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputName">Image</label>
                        <input type="file" name="image">
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