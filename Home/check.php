<?php
require '../Helpers/db.php';
require '../Helpers/functions.php';

if (!isset($_SESSION['user_id'])) {
  header('location: ' . UrlUsers("Home/"));
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_SESSION['user_email'];
  $password = Clean($_POST['password']);
  $errors = [];
  //------- errors ---------------



  if (!Validate($password, 1)) {
    $errors['password_required'] = 'password is required';
  }
  if (Validate($name, 3, 7)) {
    $errors['password-len'] = 'password must be >= 8 chars';
  }
  if (!(count($errors) > 0)) {
    $password = md5($password);
    $data = " select * from users where user_email= '$email' and user_password= '$password'";
    $op = mysqli_query($con, $data);
    if (mysqli_num_rows($op) == 1) {
      $_SESSION['flag'] = 1;
      header('location: change.php ');
    } else {
      $errors['not-found'] = ' password incorrect';
    }
  } else {
    echo 'error' . mysqli_error($con);
  }
}

?>



<!doctype html>
<html lang="en">
<!--=========head==========-->
<?php require '../Design/headTag.php'; ?>

<body>

  <!-- =======header=======--->
  <?php require '../Design/header.php'; ?>

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



    <div class="form-group">
      <label for="exampleInputPassword1">Password</label>
      <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
    </div>


    <button type="submit" class="btn btn-primary">Submit</button>
  </form>


  <!--========footer=======-->
  <?php require '../Design/footer.php'; ?>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>