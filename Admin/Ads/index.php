<?php
require '../../helpers/db.php';
require '../../helpers/valid.php';


# Fetch Roes Data .......
$sql = 'select sections.* , ads.* , users.* from sections join ads on sections.section_id =       ads.section_id
            join users  on users.user_id = ads.user_id';

$op = mysqli_query($con, $sql);


require '../layouts/header.php';
require '../layouts/nav.php';
require '../layouts/sidNav.php';
?>



<main>
    <div class="container-fluid">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard/Category/display</li>
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
                                <th>Title</th>
                                <th>Body</th>
                                <th>Date</th>
                                <th>Typr</th>
                                <th>Price</th>
                                <th>is_sold</th>
                                <th>Delete</th>
                                <th>Pic</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Body</th>
                                <th>Date</th>
                                <th>Typr</th>
                                <th>Price</th>
                                <th>is_sold</th>
                                <th>Delete</th>
                                <th>Pic</th>
                                <th>User</th>
                        </tfoot>
                        <tbody>

                            <?php 
                                        # Fetch Data ...... 
                                        for( $i=1 ; $data = mysqli_fetch_assoc($op) ; $i++){
                                      
                                    ?>

                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $data['ad_title']; ?></td>
                                <td><?php echo substr($data['ad_body'],0,20); ?></td>
                                <td><?php echo $data['ad_date']; ?></td>
                                <td><?php echo $data['section_name']; ?></td>
                                <td><?php echo $data['price']; ?></td>
                                <td><?php echo $data['is_sold']; ?></td>
                                <td><?php echo $data['deleted']; ?></td>
                                
                                <td>
                                <img src='/waseet/uploads/<?php echo $data['ad_pic']; ?>' style='height:50px;'/>
                                    </td>
                                
                                
                                <td><?php echo $data['user_name']; ?></td>

                                <td>
                                    <a href='delete.php?id=<?php echo $data['ad_id']; ?>'
                                        class='btn btn-danger m-r-1em'>Delete</a>
                                    <a href='edit.php?id=<?php echo $data['ad_id']; ?>' class='btn btn-primary m-r-1em'>Edit</a>
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
