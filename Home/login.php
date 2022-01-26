<?php
require '../Helpers/db.php';
require '../Helpers/functions.php';

if (isset($_SESSION['user_id'])) {
  header('location: ' . UrlUsers("home/"));
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = clean($_POST['email']);
  $password = clean($_POST['password']);
  $errors = [];
  //------- errors ---------------
  if (!Validate($email, 1)) {
    $errors['email_required'] = 'email is required';
  } else {
    if (!Validate($email, 2) && $email != "SA") {
      $errors['email-type'] = 'plese, enter email only';
    }
  }
  if (!Validate($password, 1)) {
    $errors['password_required'] = 'password is required';
  }
  if (!(count($errors) > 0)) {
    $password = md5($password);
    $data = " select * from users where user_email= '$email' and user_password= '$password'";
    $op = mysqli_query($con, $data);
    if (mysqli_num_rows($op) == 1) {
      $data = mysqli_fetch_assoc($op);
      $_SESSION['user_id'] = $data['user_id'];
      $_SESSION['user_name'] = $data['user_name'];
      $_SESSION['user_email'] = $data['user_email'];
      $_SESSION['user_password'] = $data['user_password'];
      $_SESSION['user_type'] = $data['user_type'];
      $_SESSION['deleted'] = $data['deleted'];


      header('location: /waseet/home/index.php ');
    } else {
      $errors['not-found'] = 'email or password incorrect';
    }
  } else {
    echo 'error' . mysqli_error($con);
  }
}

?>



<?php
$pageTitle = "Login";
require '../Design/header.php';
require '../Design/navbar.php';
require '../Ads/styleComment.php';
?>
<div class="container" style="padding-top: 100px;">
<div class="card " >
<form  method='post' action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method="POST">

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
  } elseif (isset($_SESSION['Done'])) {
    echo '<div style="color:#060;background:#ddd ;padding:20px; " >' . $_SESSION['Done']  . '</div>' . '<br>';
  }
  ?>


  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="email">
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
  </div>

  <div class="form-group">
    <p class="form-check-label" for="exampleCheck1">
      <a href="sign.php">creat new account</a>
    </p>
  </div>
  <!-----
  <div class="form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1" >
    <label class="form-check-label" for="exampleCheck1">Check me out</label>
  </div>
------>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

</div>
</div>
<!--========footer=======-->
<?php require '../Design/footer.php'; ?>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->