<?php

$isLogged =false;
if (isset($_SESSION['user_name'])) {
    
  $isLogged =true;
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a href="/Waseet/home/index.php" class="logo d-flex align-items-center">
            <img src="../Design/WLogo.jpg" alt="" height="60" width="60">
            <span></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/waseet/home/index.php">Home</a>
                </li>
                <?php if($isLogged==false) {?>
                <a class="nav-link active" aria-current="page" href="/Waseet/home/login.php">Signin/Register</a>
                <?php } 
                else{ ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        My Account
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/waseet/home/profile.php?id=<?php echo $_SESSION["user_id"]?>">Profile</a></li>
<?php if( $_SESSION["user_type"]==1) {?>
    <li><a class="dropdown-item" href="/Waseet/Admin/">Dash Board</a></li>
<?php } ?>
                        <li>
                        <li><a class="dropdown-item" href="/Waseet/Ads/myAds.php">My ADS</a></li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="/waseet/home/logout.php">SignOut</a></li>
                    </ul>
                </li>

                <?php }?>
            </ul>

        </div>
    </div>
</nav>

<?php
                    echo '<br>';
                    if (isset($_SESSION['Message'])) {
                        Messages($_SESSION['Message']);
                        # Unset Session ...
                        unset($_SESSION['Message']);
                    }
                    ?>