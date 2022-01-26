<?php
require '../Helpers/dbTransactions.php';
require '../Helpers/valid.php';
///// User data 

$currentUser =    $_SESSION['user_id'];
$current_User_Type = $_SESSION['user_type'];


$myAds = selectData("select  ads.ad_id , ads.ad_title,ads.ad_body,ads.ad_date,sections.section_name  from ads inner join sections on ads.section_id = sections.section_id where ads.user_id =$currentUser");



?>

<?php $pageTitle = "My ADS";
require '../Design/header.php';
require '../Design/navbar.php';
require '../Ads/styleComment.php'; ?>


    <main class="container" style="margin-top:100px;">
 
        <form action="<?php echo $_SERVER["PHP_SELF"];?>" $id method="POST" >
           <br>
        <div class="card mb-4 row">

<div class="card-body">


    <div class="table-responsive">
    
         <a href='/waseet/Ads/create.php' class='btn btn-primary m-r-1em'>+Create</a><hr>
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Date</th>
                    <th>Section</th>
                    <th>Action</th>
                </tr>
            </thead>
           
            <tbody>

                <?php 
                            # Fetch Data ...... 
                            while($data = mysqli_fetch_assoc($myAds)){
                          
                        ?>

                <tr>
                    <td><?php echo $data['ad_id']; ?></td>
                    <td><?php echo $data['ad_title']; ?></td>
                    <td><?php echo substr($data['ad_body'],0,40); ?></td>                
                    <td><?php echo $data['ad_date'] ; ?></td>
                    <td><?php echo $data['section_name'];?></td>


                    <td>
                        <a href='deleteAd.php?id=<?php echo $currentUser.'&ad='. $data['ad_id']; ?>'
                            class='btn btn-danger m-r-1em'>Delete</a>
                        <a href='editAd.php?id=<?php echo $data['ad_id']; ?>' class='btn btn-primary m-r-1em'>Edit</a>
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
        </form>
    </main>

    <?php require '../Design/footer.php'; ?>
