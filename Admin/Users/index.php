<?php
require '../../helpers/db.php';
require '../../helpers/functions.php';


if(isset($_SESSION['user_type'])){
    Rights($_SESSION['user_type']);
}else{
    header("Loction :".UrlUsers("home/"));
}

################################################################
//all user except SA user_id 0 ..
$sql = 'select users.*, user_type_name from users inner join users_types on users.user_type = users_types.user_type where user_id<>0';
$op = mysqli_query($con, $sql);

################################################################

require '../layouts/header.php';
require '../layouts/nav.php';
require '../layouts/sidNav.php';
?>

<main>
    <div class="container-fluid">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard/Users/display</li>
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
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>phone</th>
                                <th>Acc Type</th>
                                <th>image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($data = mysqli_fetch_assoc($op)) {
                            ?>
                                <tr>
                                    <td><?php echo $data['user_id']; ?></td>
                                    <td><?php echo $data['user_name']; ?></td>
                                    <td><?php echo $data['user_email']; ?></td>
                                    <td><?php echo $data['phone']; ?></td>
                                    <td><?php echo $data['user_type_name']; ?></td>
                                    <td> <img src="/waseet/uploads/<?php echo $data['user_pic']; ?>" height="40px" width="40px"> </td>
                                    <td>
                                        <a href='delete.php?id=<?php echo $data['user_id']; ?>' class='btn btn-danger m-r-1em'>Delete</a>
                                        <a href='edit.php?id=<?php echo $data['user_id']; ?>' class='btn btn-primary m-r-1em'>Edit</a>
                                    </td>
                                </tr>

                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require '../layouts/footer.php';
?>