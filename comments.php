<?php session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
require "database.php";
ini_set('date.timezone', 'Asia/Jakarta');

if (isset($_POST['postTitle'])) {
    $connection = setUpDBConnection();
    // $query_title = $_POST['action'];
    // $query_title = mysqli_real_escape_string($connection, $query_title);
    $db_postTitle = $_POST['postTitle'];
    // $db_postTitle = mysqli_real_escape_string($connection, $db_postTitle);
    $query = "SELECT *, tbl_customer.cust_uname as `replyer`, comments.likes as `comment_likes`, comments.cust_id as `commenter_id` FROM comments join tbl_customer on comments.cust_id = tbl_customer.cust_id join posts on posts.post_id = comments.post_id where comments.post_id = '{$db_postTitle}' order by comment_createdAt";
    $result = mysqli_query($connection, $query);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./styles/comments.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <main>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $db_commentID = $row['comment_id'];
                $db_commenterID = $row['commenter_id'];
                $db_postID = $row['post_id'];
                $db_replyContent = $row['reply_content'];
                $db_replyTo = $row['reply_to'];
                if ($db_replyTo != 'main') {
                    $db_replyTo = (int) $row['reply_to'];
                }

                $search_user = "SELECT cust_uname FROM comments join tbl_customer on comments.cust_id = tbl_customer.cust_id WHERE comment_id = '{$db_replyTo}'";
                $search_user_go = mysqli_query($connection, $search_user);
                if (mysqli_num_rows($search_user_go) > 0) {
                    $user_data = mysqli_fetch_assoc($search_user_go);
                    $username_data = $user_data['cust_uname'];
                }

                $db_replyUser = $row['replyer'];
                $db_replyLikes = $row['comment_likes'];
                $db_createdAt = $row['comment_createdAt'];
                $db_role = "Customer";
                $db_postNum = $row['post_num'];
                $cust_status = $row['cust_status'];
                $age_string = '';
                if (strtotime(date('Y-m-d H:i:s')) > strtotime($db_createdAt)) {
                    $comment_age = strtotime(date('Y-m-d H:i:s')) - strtotime($db_createdAt);
                } else {
                    $comment_age = 0;
                }
                if ($comment_age < 60) {
                    $age_string = 'Moments ago';
                } else if ($comment_age >= 60 && $comment_age < 3600) {
                    $age_acc = $comment_age / 60;
                    $age_string = (int) $age_acc . 'm ago';
                } else if ($comment_age >= 3600 && $comment_age < 86400) {
                    $age_acc = $comment_age / 3600;
                    $age_string = (int) $age_acc . ' hour ago';
                } else if ($comment_age >= 86400 && $comment_age / 86400 <= 30) {
                    $age_acc = $comment_age / 86400;
                    $age_string = (int) $age_acc . ' day ago';
                } else if ($comment_age > 2592000 && $comment_age / 2592000 <= 12) {
                    $age_acc = $comment_age / 2592000;
                    $age_string = (int) $age_acc . ' month ago';
                } else if ($comment_age > 31104000) {
                    $age_acc = $comment_age / 31104000;
                    $age_string = (int) $age_acc . ' year ago';
                }
                $db_img = $row['photo'];
                $db_lastOnline = $row['cust_datetime'];
                $flagImg = 0;
                if ($db_img == NULL) {
                    $flagImg = 1;
                }
                if (strtotime(date('Y-m-d H:i:s')) > strtotime($db_lastOnline)) {
                    $howLongAgoLogin = strtotime(date('Y-m-d H:i:s')) - strtotime($db_lastOnline);
                } else {
                    $howLongAgoLogin = 0;
                }
                $isOnline = 0;
                if ($howLongAgoLogin < 60) {
                    $howLongAgoLogin_string = 'Moments ago';
                    $isOnline = 1;
                } else if ($howLongAgoLogin >= 60 && $howLongAgoLogin < 3600) {
                    $age_acc = $howLongAgoLogin / 60;
                    $howLongAgoLogin_string = (int) $age_acc . 'm ago';
                } else if ($howLongAgoLogin >= 3600 && $howLongAgoLogin < 86400) {
                    $age_acc = $howLongAgoLogin / 3600;
                    $howLongAgoLogin_string = (int) $age_acc . ' hour ago';
                } else if ($howLongAgoLogin >= 86400 && $howLongAgoLogin / 86400 <= 30) {
                    $age_acc = $howLongAgoLogin / 86400;
                    $howLongAgoLogin_string = (int) $age_acc . ' day ago';
                } else if ($howLongAgoLogin > 2592000 && $howLongAgoLogin / 2592000 <= 12) {
                    $age_acc = $howLongAgoLogin / 2592000;
                    $howLongAgoLogin_string = (int) $age_acc . ' month ago';
                } else if ($howLongAgoLogin > 31104000) {
                    $age_acc = $howLongAgoLogin / 31104000;
                    $howLongAgoLogin_string = (int) $age_acc . ' year ago';
                }
        ?>
                <div class="comment" id="comment-<?php echo $db_commentID; ?>">
                    <div class="profile-title">
                        <?php if ($db_replyTo == 'main') : ?>
                            <h6>Reply to <a href="#thread-<?php echo $db_postID;?>">Main Post</a></h6>
                        <?php else : ?>
                            <h6>Reply to 
                                <?php 
                                    if (isset($username_data)) {
                                        ?>
                                        <a href="#comment-<?php echo $db_replyTo;?>"><?php
                                        echo $username_data . "'s post"?></a>
                                        <?php
                                    }
                                    else {
                                        echo "Deleted comment";
                                    }
                                ?></a></h6>
                        <?php endif; ?>
                        <h6><?php echo $age_string ?></h6>
                    </div>
                    <div class="profile-content">
                        <div class="profile-stats">
                            <div class="profile-pic">
                                <div class="pic-wrap">
                                    <?php if ($flagImg) : ?>
                                        <img src="profile_pictures/default.png" alt="">
                                    <?php endif; ?>
                                </div>
                                <h5><?php echo $db_replyUser ?></h5>
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
                                    <?php echo "Customer" ?>
                                </p>
                                <p>
                                    <i class="fas fa-pencil-alt"></i>
                                    <?php echo $db_postNum . ' posts' ?>
                                </p>
                                <p><i class="fas fa-sign-in-alt"></i> <?php echo $howLongAgoLogin_string ?></p>
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
                                <?php echo htmlspecialchars_decode($db_replyContent) ?>
                            </div>
                        </div>
                    </div>
                    <div class="post-add-actions">
                        <div class="post-likes">
                            <?php
                            $cust_id = $_SESSION['customer']['cust_id'];
                            $check_like_state = "SELECT * FROM tbl_likes WHERE commentOrPost = 'comment' AND id = '{$db_commentID}' AND cust_id = '{$cust_id}'";
                            $check_go = mysqli_query($connection, $check_like_state);
                            $liked = 0;
                            if (mysqli_num_rows($check_go) > 0) {
                                $liked = 1;
                            }
                            ?>
                            <h6><span><?php echo $db_replyLikes; ?></span> users favorited this post</h6>
                        </div>
                        <div class="post-actions">
                            <div class="heart-reply">
                                <?php
                                if ($liked) {
                                ?>
                                    <i class="far fa-heart nonMain liked"></i>
                                <?php } else {
                                ?>
                                    <i class="far fa-heart nonMain"></i>
                                <?php }
                                ?>
                                <i class="fas fa-reply nonMain"></i>
                            </div>
                            <?php
                            if ($_SESSION['customer']['cust_id'] == $db_commenterID) {
                            ?>
                                <div class="delete">
                                    <i class="fas fa-trash"></i>
                                </div>
                            <?php }
                            ?>
                        </div>
                    </div>
                </div>
        <?php    }
        } ?>
    </main>
</body>

</html>