
 <?php
require '../Helpers/db.php';
require '../Helpers/functions.php';
if(isset( $_GET['id'])){
    $section_id = $_GET['id'];
}
else{
$search = $_GET['search'];}

if(!empty($section_id ))  {

 $data_ads ="select sections.* , ads.* , users.* from sections join ads on sections.section_id =       ads.section_id
            join users  on users.user_id = ads.user_id
           where ads.deleted = 0 and sections.section_id = $section_id and users.deleted = 0 ";

      $op_ads = mysqli_query( $con , $data_ads);
      if(!$op_ads){
        echo 'error' . mysqli_error($con);
      }
    }elseif(!empty($search)){     

 $data_ads ="select sections.* , ads.* , users.* from sections join ads on sections.section_id =       ads.section_id
            join users  on users.user_id = ads.user_id
           where ads.deleted = 0 and users.deleted = 0  and  ads.ad_title  like '%$search%'";

      $op_ads = mysqli_query( $con , $data_ads);
      if(!$op_ads){
        echo 'error' . mysqli_error($con);
      }
    }

else{
    
     header('location: '. UrlUsers("Home/"));
}


?>


    <!--========= header==========-->
    <?php require '../Design/header.php ';
    require '../Design/navbar.php';
    ?>
       

       <main class="container" style="background-color: #fff;">

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

                                <div class="card-img-top">
                                    <img src="../Uploads/<?php echo $data_adss['ad_pic'] ; ?> " alt="" height="400"
                                        width="150" style="width:100%; text-align: center;">
                                </div>

                                <h2 class="entry-title">
                                    <a href="/Waseet/Ads/ad-details.php?id=<?php echo $data_adss['ad_id']; ?>">
                                        <?php   echo $data_adss['ad_title'] ; ?>
                                    </a>
                                </h2>


                                <div class="entry-content">
                                    <p style="word-break:break-all">
                                        <?php   echo substr( $data_adss['ad_body'],0,100) ; ?>

                                    </p>


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

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

