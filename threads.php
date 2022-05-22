<?php session_start();
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
require "database.php";
ini_set('date.timezone', 'Asia/Jakarta');

if (isset($_POST['action'])) {
    $connection = setUpDBConnection();
    $query_title = $_POST['action'];
    // $query_title = mysqli_real_escape_string($connection, $query_title);
    $query = "SELECT * FROM posts join tbl_customer on posts.cust_uname = tbl_customer.cust_uname where post_id='{$query_title}'";
    $result = mysqli_query($connection, $query);
    $query_date = "SELECT DATE_FORMAT(created_at, '%M %D %Y %h:%i %p') as datestring FROM posts join tbl_customer on posts.cust_uname = tbl_customer.cust_uname where post_id='{$query_title}'";
    $result = mysqli_query($connection, $query);
    $result_date = mysqli_query($connection, $query_date);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $db_postID = $row['post_id'];
        $db_postContent = $row['post_content'];
        $db_categoryName = $row['mcat_name'];
        $db_postTitle = $row['post_title'];
        $db_postUser = $row['cust_uname'];
        $db_postViews = $row['views'];
        $db_postComments = $row['comments'];
        $db_createdAt = $row['created_at'];
        $db_postHot = $row['hot'];
        $cust_status = $row['cust_status'];
        $db_likes = $row['likes'];
        $get_tcat = "SELECT tcat_id FROM tbl_mid_category WHERE mcat_id = '{$row['mcat_id']}'";
        $get_tcat_go = mysqli_query($connection, $get_tcat);
        $row_gettcat = mysqli_fetch_assoc($get_tcat_go);
        $get_tcat_name = "SELECT tcat_name FROM tbl_top_category WHERE tcat_id = '{$row_gettcat['tcat_id']}'";
        $get_tcat_name_go = mysqli_query($connection, $get_tcat_name);
        $row_tcatName = mysqli_fetch_assoc($get_tcat_name_go);
        $fin_tcat = $row_tcatName['tcat_name'];
        if (strtotime(date('Y-m-d H:i:s')) > strtotime($row['created_at'])) {
            $db_postAge = strtotime(date('Y-m-d H:i:s')) - strtotime($row['created_at']);
        }
        else {
            $db_postAge = 0;
        }
        $db_postNum = $row['post_num'];
        $age_string = '';
        if (mysqli_num_rows($result_date) > 0) {
            $row_date = mysqli_fetch_assoc($result_date);
            $date_string = $row_date['datestring'];
        }
        if ($db_postAge < 60) {
            $age_string = 'Moments ago';
        }
        else if ($db_postAge >= 60 && $db_postAge < 3600) {
            $age = $db_postAge / 60;
            $age_string = (int) $age . 'm ago';
        }
        else if ($db_postAge >= 3600 && $db_postAge < 86400) {
            $age = $db_postAge / 3600;
            $age_string = (int) $age . ' hour ago';
        }
        else if ($db_postAge >= 86400 && $db_postAge / 86400 <= 30) {
            $age = $db_postAge / 86400;
            $age_string = (int) $age . ' day ago';
        }
        else if ($db_postAge > 2592000 && $db_postAge / 2592000 <= 12) {
            $age = $db_postAge / 2592000;
            $age_string = (int) $age . ' month ago';
        }
        else if ($db_postAge > 31104000) {
            $age = $db_postAge / 31104000;
            $age_string = (int) $age . ' year ago';
        }
        $db_img = $row['photo'];
        $db_lastOnline = $row['cust_datetime'];
        $flagImg = 0;
        if ($db_img == NULL) {
            $flagImg = 1;
        }
        if (strtotime(date('Y-m-d H:i:s')) > strtotime($db_lastOnline)) {
            $howLongAgoLogin = strtotime(date('Y-m-d H:i:s')) - strtotime($db_lastOnline);
        }
        else {
            $howLongAgoLogin = 0;
        }
        $isOnline = 0;
        if ($howLongAgoLogin < 60) {
            $howLongAgoLogin_string = 'Moments ago';
            $isOnline = 1;
        }
        else if ($howLongAgoLogin >= 60 && $howLongAgoLogin < 3600) {
            $age_acc = $howLongAgoLogin / 60;
            $howLongAgoLogin_string = (int) $age_acc . 'm ago';
        }
        else if ($howLongAgoLogin >= 3600 && $howLongAgoLogin < 86400) {
            $age_acc = $howLongAgoLogin / 3600;
            $howLongAgoLogin_string = (int) $age_acc . ' hour ago';
        }
        else if ($howLongAgoLogin >= 86400 && $howLongAgoLogin / 86400 <= 30) {
            $age_acc = $howLongAgoLogin / 86400;
            $howLongAgoLogin_string = (int) $age_acc . ' day ago';
        }
        else if ($howLongAgoLogin > 2592000 && $howLongAgoLogin / 2592000 <= 12) {
            $age_acc = $howLongAgoLogin / 2592000;
            $howLongAgoLogin_string = (int) $age_acc . ' month ago';
        }
        else if ($howLongAgoLogin > 31104000) {
            $age_acc = $howLongAgoLogin / 31104000;
            $howLongAgoLogin_string = (int) $age_acc . ' year ago';
        }
    }
    $update_view_count = "UPDATE posts SET views = views + 1 WHERE post_id = '{$db_postID}'";
    $update_view_count_go = mysqli_query($connection, $update_view_count);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./styles/threads.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <main>
        <div class="thread" id="thread-<?php echo $db_postID;?>">
            <div class="thread-title">
                <h5>Thread in : <?php echo $db_categoryName . ' (' . $fin_tcat . ')'?></h5>
                <h2><?php echo $db_postTitle?></h2>
                <h6>Posted on <?php echo $date_string?> by <?php if (isset($db_postUser)) echo $db_postUser; else echo "Deleted Account";?></h6>
                <h6><?php echo $age_string?></h6>
                <?php
                    if ($_SESSION['customer']['cust_uname'] == $db_postUser) {
                    ?>
                        <button id="deleteThreadBtn" style="margin: 0; padding: 8px; border-radius: 10px; border: none; outline: none; background-color: red; color: white; cursor: pointer;">Delete Thread</button>
                    <?php }
                ?>
                <hr>
            </div>
            <div class="profile-title">
                <h6>Main Post</h6>
                <h6><?php echo $age_string?></h6>
            </div>
            <div class="profile-content">
                <div class="profile-stats">
                    <div class="profile-pic">
                        <div class="pic-wrap">
                            <?php if ($flagImg) : ?>
                                <img src="profile_pictures/default.png" alt="">
                            <?php endif; ?>
                        </div>
                        <h5><?php if (isset($db_postUser)) echo $db_postUser; else echo "Deleted Account";?></h5>
                        <h6>
                            <?php if ($isOnline) : ?>
                                Online
                            <?php else : ?>
                                Offline
                            <?php endif; ?>
                        </h6>
                    </div>
                    <div class="detail-stats">
                        <p><i class="far fa-user"></i>
                            <?php echo "Customer";?>
                        </p>
                        <p>
                            <i class="fas fa-pencil-alt"></i> 
                            <?php if (isset($db_postNum)) echo $db_postNum . ' posts'; else echo "-";?>
                        </p>
                        <p><i class="fas fa-sign-in-alt"></i> <?php if (isset($db_postUser)) echo $howLongAgoLogin_string; else echo "-";?></p>
                        <p>
                            <i class="fas fa-info-circle"></i> 
                            <?php if ($cust_status == 1) : ?>
                                Active
                            <?php else : ?>
                                Inactive
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <div class="post-content-details">
                    <div class="post-content">
                        <?php echo htmlspecialchars_decode($db_postContent)?>
                    </div>
                </div>
            </div>
            <div class="post-add-actions">
                <div class="post-likes">
                    <?php
                        $cust_id = $_SESSION['customer']['cust_id'];
                        $check_like_state = "SELECT * FROM tbl_likes WHERE commentOrPost = 'post' AND id = '{$db_postID}' AND cust_id = '{$cust_id}'";
                        $check_go = mysqli_query($connection, $check_like_state);
                        $liked = 0;
                        if (mysqli_num_rows($check_go) > 0) {
                            $liked = 1;
                        }
                    ?>
                    <h6><span><?php echo $db_likes; ?></span> users favorited this post</h6>
                </div>
                <div class="post-actions">
                    <div class="heart-reply">
                        <?php 
                            if ($liked) {
                                ?>
                                <i class="far fa-heart main liked"></i> 
                            <?php }
                            else {
                                ?>
                                <i class="far fa-heart main"></i> 
                            <?php }
                        ?>
                        <i class="fas fa-reply main"></i>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>