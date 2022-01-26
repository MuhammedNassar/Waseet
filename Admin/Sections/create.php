<?php
require '../../helpers/db.php';
require '../../helpers/functions.php';

if(isset($_SESSION['user_type'])){
    Rights($_SESSION['user_type']);
}else{
    header("Loction :".UrlUsers("home/"));
}


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $section_name = Clean($_POST['section_name']);
    $errors = [];
    // name
    if (!Validate($section_name, 1)) {
        $errors['Name'] = "Required Field";
    } elseif (!Validate($$section_name, 6)) {
        $errors['Name'] = "Invalid String";
    }
    if (count($errors) > 0) {
        $Message = $errors;
    } else {

        $sql = "insert into sections (section_name , is_active) values ('$section_name' , 1)";
        $op  = mysqli_query($con, $sql);

        if ($op) {
            $Message = ["Message" => "Raw Inserted"];
        } else {
            $Message = ["Message" => "Error Try Again " . mysqli_error($con)];
        }
    }
    $_SESSION['Message'] = $Message;
    header('Location: index.php');
}

require '../layouts/header.php';
require '../layouts/nav.php';
require '../layouts/sidNav.php';
?>


<main>
    <div class="container-fluid">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard/Sections/Create</li>

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
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

                    <div class="form-group">
                        <label for="exampleInputName">Title</label>
                        <input type="text" class="form-control" id="exampleInputName" name="section_name" aria-describedby="" placeholder="Enter Title">
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
require '../layouts/footer.php';
?>