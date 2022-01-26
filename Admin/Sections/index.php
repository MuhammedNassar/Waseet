<?php
require '../../helpers/db.php';
require '../../helpers/functions.php';

if(isset($_SESSION['user_type'])){
    Rights($_SESSION['user_type']);
}else{
    header("Loction :".UrlUsers("home/"));
}



$sql = 'select * from sections';
$sectionOp = mysqli_query($con, $sql);


require '../layouts/header.php';
require '../layouts/nav.php';
require '../layouts/sidNav.php';
?>

<main>
    <div class="container-fluid">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard/Sections/display</li>
            <?php 
            echo '<br>';
           if(isset($_SESSION['Message'])){
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
                                <th>title</th>
                               
                                <th>Action</th>
                            </tr>
                        </thead>
                      
                        <tbody>

                            <?php 
                                        # Fetch Data ...... 
                                        while($data = mysqli_fetch_assoc($sectionOp)){
                                      
                                    ?>

                            <tr>
                                <td><?php echo $data['section_id']; ?></td>
                                <td><?php echo $data['section_name']; ?></td>
                          
                                <td>
                                    <a href='delete.php?id=<?php echo $data['section_id']; ?>'
                                        class='btn btn-danger m-r-1em'>Delete</a>
                                    <a href='edit.php?id=<?php echo $data['section_id']; ?>' class='btn btn-primary m-r-1em'>Edit</a>
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
