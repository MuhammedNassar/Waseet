<?php
require '../../helpers/db.php';
require '../../helpers/valid.php';

//----==== all users types=====--
$sql = 'select * from users_types';
$op = mysqli_query($con, $sql);

require '../layouts/header.php';
require '../layouts/nav.php';
require '../layouts/sidNav.php';
?>



<main>
    <div class="container-fluid">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard/Roles/display</li>
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
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                        </tfoot>
                        <tbody>

                            <?php 
                                        # Fetch Data ...... 
                                        for($i=1 ; $data = mysqli_fetch_assoc($op) ; $i++){
                                      
                                    ?>

                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $data['user_type_name']; ?></td>

                                <td>
                                    
                                     <a href='edit.php?id=<?php echo $data['user_type']; ?>' class='btn btn-primary m-r-1em'>Edit</a>
                                    
                                    <?php 
                                    if($data['user_type'] > 3){  ?> 
                                    <a href='delete.php?id=<?php echo $data['user_type']; ?>'
                                        class='btn btn-danger m-r-1em'>Delete</a>
                                    <?php } ?>
                                    
                                   
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
