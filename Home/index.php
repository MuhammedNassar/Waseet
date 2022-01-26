 <?php
require '../Helpers/db.php';

 $data_ads ="select sections.* , ads.* , users.* from sections join ads on sections.section_id =       ads.section_id
            join users  on users.user_id = ads.user_id where users.deleted <> 1  and ads.deleted <> 1 " ;


      $op_ads = mysqli_query( $con , $data_ads);
      if(!$op_ads){
        echo 'error' . mysqli_error($con);
      }


?>

 <?php
 $pageTitle="Waseet";
 require '../Design/header.php ';
    require '../Design/navbar.php';
    require '../Ads/styleComment.php';
    ?>

 <main class="container" style="background-color: #dee1e3;">

     <div class="row">

         <section class="container" style="padding-top: 100px; ">
             <div class="container-fluid" data-aos="fade-up">

                 <div class="row">

                     <div class="col-lg-8 ">
                         <!-- ========= article ========-->
                         <div class="col-md-12">

                             <div class="col-md-12">
                                 <?php
                while($data_adss = mysqli_fetch_assoc($op_ads)){ ?>

                                 <article class="card">

                                     <div class="card-img-top" style="justify-content: center;text-align: center; background-color: #fff;">
                                         <img src="../Uploads/<?php echo $data_adss['ad_pic'] ; ?> " alt="" height="350px"
                                             width="350px">
                                             <hr>
                                     </div>
                                     <div class="card-header">
                                         <hr>
                                         <h2 class="entry-title">
                                             <a href="/Waseet/Ads/ad-details.php?id=<?php echo $data_adss['ad_id']; ?>">
                                                 <?php   echo $data_adss['ad_title'] ; ?>
                                             </a>
                                             <hr>
                                         </h2>


                                         <div class="entry-content">
                                             <p style="word-break:break-all">
                                                 <?php   echo substr( $data_adss['ad_body'],0,100) ; ?>

                                             </p>


                                         </div>
                                     </div>
                                 </article><!-- End blog entry -->
                                 <hr>
                                 <?php   } ?>
                             </div>
                         </div>


                     </div><!-- End blog entries list -->
                     <div class="col-md-4">
                         <?php require '../Design/slidebar.php';?>
                     </div>
                     <!--============slideba========-->
                     <?php // require '../Design/slidebar.php'; ?>


                 </div><!-- End sidebar -->

             </div><!-- End blog sidebar -->


         </section><!-- End Blog Section -->
     </div>
 </main><!-- End #main -->




 <?php require '../Design/footer.php '; ?>