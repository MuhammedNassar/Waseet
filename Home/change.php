<?php
require '../Helpers/db.php';
require '../Helpers/dbTransactions.php';
require '../Helpers/functions.php';

if (!isset($_SESSION['user_id'])) {
  header('location: ' . UrlUsers("Home/"));
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $password = Clean($_POST['password']);
  $sql=selectData("select user_email from users where user_id=".$_SESSION['user_id']);
  $run=mysqli_fetch_assoc($sql);
  $email=$run["user_email"];
  
  $passwordN = Clean($_POST['passwordN']);
  $passwordC = Clean($_POST['passwordC']);
  $errors = [];

  //------- errors ---------------
  if (!Validate($password, 1)) {
    $errors['password_require'] = 'password is required';
  } else {
    if ((Validate($password, 3, 7))) {
      $errors['password-len'] = 'password must be >= 8 chars';
    }
  }
  if (!Validate($passwordN, 1)) {
    $errors['password_require'] = 'new password is required';
  } else {
    if ((Validate($passwordN, 3, 7))) {
      $errors['password-len'] = 'new password must be >= 8 chars';
    }
  }
  if (!Validate($passwordC, 1)) {
    $errors['password_require'] = 'Confirm password is required';
  } else {
    if ((Validate($passwordC, 3, 7))) {
      $errors['password-len'] = 'Confirm password must be >= 8 chars';
    }
  }
  if ($passwordN !== $passwordC) {
    $errors['Password'] = 'Password mismatch';
  }
  if (!(count($errors) > 0)) {
    $password = md5($password);
    $data = "select * from  users where user_email= '$email' and  user_password='$password'";

    $op = mysqli_query($con, $data);
    if (mysqli_num_rows($op) > 0) {
      $sql = "update users set user_password='$password' where user_email='$email'";
      $run = mysqli_query($con, $sql);
      $errors['done'] = 'password changed';
    } else {
      $errors['not-found'] = 'password incorrect';
    }
  } else {
    echo 'error' . mysqli_error($con);
  }
}

?>



<!-- =======header=======--->
<?php 
$pageTitle="Change my password";
require '../Design/header.php';
require '../Design/navbar.php';
require'../Ads/styleComment.php';
?>

<form style="width:70%; margin:100px auto" method='post' action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>'>

  <!--==== errors in form ======----->
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
<div class="card">
  <div class="form-group">
    <label for="exampleInputPassword1"> <strong>Change Password : <?php echo $_SESSION['user_name']; ?></strong> </label>
    <hr>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Please enter old password" name="password">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="choose a New password" name="passwordN">

  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Confirm your New password" name="passwordC">
    <hr>
  </div>
  <div class="form-group">
  <button type="submit" class="btn btn-primary">change</button>
  </div>
</div>
</form>

<!--========footer=======-->
<?php require '../Design/footer.php'; ?>