<?php
require '../Helpers/db.php';
require '../Ads/styleComment.php';


if(!$_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['search'])) ){
    
    $_SESSION['search'] = Clean($_GET['search']);
    
   header("location: ".UrlUsers("home/"));
}

$section = " select * from sections where is_active =1 ";
 $op = mysqli_query( $con , $section);
?>

<div class="card d-flex flex-column flex-shrink-0 p-5 text-black bg-light rounded">
    <div class="sidebar">
      <h3 class="sidebar-title">FIND</h3>
      <div class="sidebar-item search-form">
        <form action="/Waseet/Home/search.php?name=mmm" method='get'>
          <input type="text" name='search'>
          <button class="btn btn-dark" type="submit"></i> Search</button>
        </form>
      </div><!-- End sidebar search formn-->

      <h3 class="sidebar-title" >Categories</h3>
      <div class="sidebar-item categories">
        <ul class="list-group">
            <!--===========show sections==========-->
            <?php 
            while($sections = mysqli_fetch_assoc($op)){ ?>
          <li class="list-group-item"><a href="search.php?id=<?php echo $sections['section_id'] ?>"> <?php echo $sections['section_name']; ?> </a></li>
         <?php   } ?>

        </ul>

  </div><!-- End sidebar categories--> 
 </div><!-- End sidebar -->

  </div><!-- End blog sidebar -->