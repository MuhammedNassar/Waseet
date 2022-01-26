<?php
require 'ad-detailsOperations.php';



$commentFetch = selectData("select comment_id,comment_text,comment_date ,comments.user_id,users.user_name,users.user_pic FROM
                                                users inner join comments on users.user_id=comments.user_id  where ad_id=$ad_id  ORDER by  comment_date DESC;");

if (!isset($_SESSION['user_id'])) {
    header("Location: /waseet/Home/login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["subComText"])) {
        $cTxt = htmlspecialchars($_POST["subComText"]);
        $cId =   $_COOKIE["commentId"];
        $insertSub = insertData("insert into sub_comments(sub_comment_text,sub_comment_date,comment_id,user_id) values ('$cTxt','" . date("Y-m-d") . "',$cId,$currentUser)");
        echo "<script> alert('reply Added to Comment')</script>";
        $_POST["subComText"] = "";
    } elseif (!empty($_POST["mainComment"])) {
        $cTxt = htmlspecialchars($_POST["mainComment"]);

        $insertSub = insertData("insert into comments(comment_text,comment_date,ad_id,user_id) values ('$cTxt','" . date("Y-m-d") . "',$ad_id,$currentUser)");
        $_POST["mainComment"] = "";
        $commentFetch;
        echo "<script> alert('Comment Added to Ad')</script>";
    }
    header("location:" . UrlUsers("ads/ad-details.php?id=$ad_id"));
}


// if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['subComment']) && !isset($_POST["mainComment"])) {

//     $sub =  $_POST["subComment"];

//     $cDate = date("Y-m-d");
//     $main = $_POST["mainComment"];
//     if (!isset($main)) {
//         $sl = insertData("insert into sub_comments (sub_comment_text,sub_comment_date,comment_id,user_id) values ('$sub','$cDate',$getCurrentComment,$currentUser)");
//         $getCurrentComment = "";
//     } else {
//     }
// }
// if ($_SERVER['REQUEST_METHOD'] == "POST" && !isset($_POST['subComment']) && isset($_POST["mainComment"])) {
//     $main =  $_POST["mainComment"];
//     $cDate = date("Y-m-d");
//     $sl = insertData("insert into comments (comment_text,comment_date,user_id,ad_id) values ('$main','$cDate',$currentUser,$ad_id)");
// }

?>
<html lang="en">
<?php $pageTitle = "Display Ad";
require '../Design/header.php';
require '../Design/navbar.php';
require 'styleComment.php';
?>
<main id="main">
    <!-- ======= Breadcrumbs ======= -->

    <!-- End Breadcrumbs -->
    <section class="container" style="padding-top: 100px;">
        <div class="row">

            <div class="col-md-1"></div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-img-top" style="justify-content: center;text-align: center; background-color: #fff;">

                        <img src="../uploads/<?php echo $imgUrl ?>" alt="..." height="400px" width="400px">
                        <hr>
                    </div>

                    <div class="card-header">
                        <hr>
                        <div class="row">
                            <div class="col-md-8">
                                <img height="60px;" width="60px;" style="border-radius: 40%;" src="../uploads/<?php echo $user_pic; ?>" alt="Personal Pic">
                                <i style="padding-left: 10px;" class="fas fa-user" style="color: black;">
                                    <?php echo $user_name ?> </i>
                                <i class="fas fa-calendar-alt" style="color: black;"> <?php echo $ad_date ?> </i>
                                <i style="padding-left: 10px;" class="fas fa-phone" style="color: black;">
                                    <?php echo $phone ?> </i>
                                <i style="padding-left: 10px;" class="fas fa-dollar-sign" style="color: black;">
                                    <?php echo $price ?> </i>
                            </div>
                            <?php if ($currentUser == $user_id || ($current_User_Type == 1 || $current_User_Type == 2)) { ?>
                                <div class="col-md-4" style="text-align: right;">
                                    <div class="dropdown">
                                        <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i style="color: black;" class="fas fa-edit"></i>
                                        </a>
                                        <ul style="color: black;" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <li><a class="dropdown-item" href="editAd.php?id=<?php echo $ad_id; ?>">Edit</a>
                                            </li>
                                            <li><a class="dropdown-item" href="<?php echo "deleteAd.php?id=".$currentUser ."&ad=".$ad_id; ?> " >Delete</a></li>
                                        </ul>
                                    </div>
                                <?php } ?>
                                </div>
                        </div>
                        <hr>
                        <div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $ad_title ?></h5>
                                <p class="card-text"><?php echo $ad_body ?></p>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <h4 style="padding-top: 10px;">Total comments <?php echo $total_comments; ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-1"></div>
        <hr>
        <div class="container">

            <div class="row" style="float: left;">
                <?php while ($comment = mysqli_fetch_assoc($commentFetch)) { ?>
                    <div class="col-md-8" style="flex-wrap: wrap;">
                        <div class="comment-box comments-list ml">
                            <div class="comment-head">
                                <img class="comment-name by-img" height="24.5px" width="24.5px" style="width:inherit;" src="<?php echo '../uploads/' . $comment['user_pic'] ?>" alt="">
                                <h6 class="comment-name "><a href="<?php echo '/Waseet/Home/DisplayUser.php?id=' . $comment['user_id']; ?>"><?php echo $comment["user_name"] ?></a>
                                    </a>
                                </h6>
                                <span><?php echo $comment["comment_date"] ?></span>
                                <form action=" <?php echo $_SERVER["PHP_SELF"] . '?id=' . $ad_id; ?>" method="POST">
                                    <div id="contact">
                                        <i id="<?php echo $comment["comment_id"]; ?>" onclick="GFG_click(this.id)" class="fa fa-reply " aria-hidden="true" data-toggle="modal" data-target="#comMod"></i>
                                    </div>
                                    <div id="comMod" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">
                                                        Reply
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="comForm" action="<?php echo UrlUsers('Ads/ad-details.php?id=' . $ad); ?>" method="POST">
                                                        <input name="subComText" type="text" class="form-control">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Reply</button>
                                                </div>
                                                
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php if ($currentUser == $comment["user_id"] || ($current_User_Type == 1 || $current_User_Type == 2)) { ?>
                        <a href="deleteComment.php?id=<?php echo $comment["comment_id"] . '&ad=' . $ad_id; ?>">
                            <i class="fas fa-minus-circle"></i> </a>
                    <?php } ?>
            </div>
            <div class="comment-content">
                <div style="word-break: break-all;">
                    <?php echo $comment["comment_text"]; ?>
                </div>
            </div>
        </div>
        <?php
                    $getComment = $comment['comment_id'];
                    $sql = "select sub_comments.* , users.user_name,users.user_pic from sub_comments inner join 
                                comments on sub_comments.comment_id=comments.comment_id inner join users on sub_comments.user_id =users.user_id 
                                   where comments.comment_id=" . $getComment;
                    $subCommentObj = selectData($sql);
                    while ($subCommentData = mysqli_fetch_assoc($subCommentObj)) { ?>
            <div class="comment-box reply-list col-md-8">
                <div class="comment-head">
                    <img class="comment-name by-img" height="24.5px" width="24.5px" style="width:inherit;" src="<?php echo '../uploads/' . $subCommentData['user_pic'] ?>" alt="">
                    <h6 class="comment-name "><a href="<?php echo '/Waseet/Home/DisplayUser.php?id=' . $subCommentData['user_id']; ?>"><?php echo $subCommentData["user_name"] ?></a>
                    </h6>
                    <span><?php echo $subCommentData["sub_comment_date"] ?></span>
                    <?php if ($currentUser == $comment["user_id"] || ($current_User_Type == 1 || $current_User_Type == 2)) { ?>
                        <a href="deleteSubComment.php?id=<?php echo $subCommentData["sub_comment_id"] . '&ad=' . $ad_id; ?>">
                            <i class="fas fa-minus-circle"></i> </a>
                    <?php } ?>
                </div>
                <div class="comment-content">
                    <Span style="word-break: break-all;">
                        <?php echo $subCommentData["sub_comment_text"]; ?>
                    </Span>
                </div>
            </div>
        <?php } ?>
        </div>
    <?php } ?>

    <br>
    <form action=" <?php echo $_SERVER["PHP_SELF"] . '?id=' . $ad_id; ?>" name="AddComment" method="POST">
        <div class="form-group col-md-8" style="padding-top: 40px;">
            <br>
            <textarea class="form-control" name="mainComment" id="mainComment" cols="40" rows="5"></textarea><br>
            <button id="btnSub" type="submit" class="btn btn-dark " disabled>POST</button>
        </div>
    </form>




    </div>
    </div>
    </section>
    <!-- End Blog Single Section -->
</main>

<!-- End #main -->
<!-- ======= Footer ======= -->
<a href="#" class="back-to-top d-flex align-items-center justify-content-center active"><i class="bi bi-arrow-up-short"></i></a>
<script type="text/javascript">
    function GFG_click(clicked) {
        document.getElementById(clicked).style.display = "block";
        $getCurrentComment = clicked;
        document.cookie = "commentId=" + clicked;

    }
    $(document).ready(function() {
        $('#btnSub').attr('disabled', true);
        $('#mainComment').keyup(function() {
            if ($(this).val().length != 0)
                $('#btnSub').attr('disabled', false);
            else
                $('#btnSub').attr('disabled', true);
        })
    });
</script>
<?php require '../Design/footer.php'; ?>