<?php
require_once('./header.php');
require "database.php";
ini_set('date.timezone', 'Asia/Jakarta');
$connection = setUpDBConnection();
$valid = 0;
$now = date('Y-m-d H:i:s');
$time_now = strtotime($now);

if (isset($_SESSION['customer'])) {
    $valid = 1;
    $db_username = $_SESSION['customer']['cust_uname'];
    $db_email = $_SESSION['customer']['cust_email'];
    $query = "SELECT * FROM tbl_customer WHERE cust_uname = '{$db_username}' AND cust_email = '{$db_email}'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $db_postNum = $row['post_num'];
        $db_img = $row['photo'];
        $db_userID = $row['cust_id'];
        $cust_status = $row['cust_status'];
        $flagImg = 0;
        if ($db_img == NULL) {
            $flagImg = 1;
        }
        $db_lastOnline = $row['cust_datetime'];
        if (strtotime(date('Y-m-d H:i:s')) > strtotime($db_lastOnline)) {
            $howLongAgoLogin = strtotime(date('Y-m-d H:i:s')) - strtotime($db_lastOnline);
        } else {
            $howLongAgoLogin = 0;
        }
        if ($howLongAgoLogin < 60) {
            $howLongAgoLogin_string = 'Moments ago';
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
        $query_active = "SELECT mcat_id, sum(count) as `count` from (select mcat_id as `mcat_id`, count(comment_id) as `count` FROM comments join posts on posts.post_id = comments.post_id WHERE comments.cust_id = '{$db_userID}' group by mcat_id union select mcat_id as `mcat_id`, count(post_id) as `count` FROM posts WHERE posts.cust_id = '{$db_userID}' group by mcat_id) alias group by mcat_id order by sum(count) desc limit 1";
        $query_active_go = mysqli_query($connection, $query_active);
        $active_catgroup = 'None';
        $most_active = "None";
        if (mysqli_num_rows($query_active_go) > 0) {
            $row_active = mysqli_fetch_assoc($query_active_go);
            $mcat_active = $row_active['mcat_id'];
            $query_catgroup = "SELECT tcat_name, mcat_name FROM tbl_mid_category join tbl_top_category on tbl_mid_category.tcat_id = tbl_top_category.tcat_id WHERE mcat_id = '{$mcat_active}'";
            $query_catgroup_go = mysqli_query($connection, $query_catgroup);
            $row_catgroupactive = mysqli_fetch_assoc($query_catgroup_go);
            $active_catgroup = $row_catgroupactive['tcat_name'];
            $most_active = $row_catgroupactive['mcat_name'];
        }
        $post_likes = "SELECT likes FROM posts WHERE cust_id = '{$db_userID}'";
        $post_likes_go = mysqli_query($connection, $post_likes);
        $post_likes_amount = 0;
        if (mysqli_num_rows($post_likes_go) > 0) {
            while ($pl_row = mysqli_fetch_assoc($post_likes_go)) {
                $post_likes_amount += (int) $pl_row['likes'];
            }
        }

        $c_likes = "SELECT likes FROM comments WHERE cust_id = '{$db_userID}'";
        $c_likes_go = mysqli_query($connection, $c_likes);
        $c_likes_amount = 0;
        if (mysqli_num_rows($c_likes_go) > 0) {
            while ($cl_row = mysqli_fetch_assoc($c_likes_go)) {
                $c_likes_amount += (int) $cl_row['likes'];
            }
        }
        $final_likes = $post_likes_amount + $c_likes_amount;
    }
} else {
    header("Location: login.php");
}
?>

<div class="page-banner">
    <div class="inner">
        <h3 style="color: black;">Profile</h3>
    </div>
</div>
<?php if ($valid) : ?>
    <main>
        <div class="profile">
            <div class="profile-title">
                <h6><?php echo $db_username ?>'s Profile</h6>
            </div>
            <div class="profile-content">
                <div class="profile-stats">
                    <div class="profile-pic">
                        <div class="pic-wrap">
                            <?php if ($flagImg) : ?>
                                <img src="profile_pictures/default.png" alt="">
                            <?php endif; ?>
                        </div>
                        <h5><?php echo $db_username ?></h5>
                        <h6>
                            <?php if (isset($_SESSION['customer'])) { ?>
                                Online
                            <?php } ?>
                        </h6>
                    </div>
                    <div class="detail-stats">
                        <p><i class="far fa-user"></i> <?php echo "Customer" ?></p>
                        <p><i class="fas fa-pencil-alt"></i> <?php echo $db_postNum . ' posts' ?></p>
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
                <div class="profile-info">
                    <div class="about-me">
                        <h5>About me</h5>
                        <table>
                            <tr>
                                <td>Full name</td>
                                <td>: <?php echo $_SESSION['customer']['cust_name']; ?></td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;">: <?php echo $_SESSION['customer']['cust_address']; ?></td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>: <?php echo $_SESSION['customer']['cust_phone']; ?></td>
                            </tr>
                            <tr>
                                <td>City</td>
                                <td>: <?php echo $_SESSION['customer']['cust_city']; ?></td>
                            </tr>
                            <tr>
                                <td>Country</td>
                                <td>:
                                    <?php
                                    $countryID = $_SESSION['customer']['cust_country'];
                                    $get_cty = "SELECT country_name FROM tbl_country WHERE country_id = '{$countryID}'";
                                    $get_cty_go = mysqli_query($connection, $get_cty);
                                    $row_gc = mysqli_fetch_assoc($get_cty_go);
                                    $cty = $row_gc['country_name'];
                                    echo $cty;
                                    ?>
                                </td>
                            </tr>
                            <tr id="last">
                                <td>Postal Code</td>
                                <td>: <?php echo $_SESSION['customer']['cust_zip']; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="post-info">
                        <div class="additional-info">
                            <h6>Additional Information</h6>
                            <table>
                                <tr>
                                    <td>Username</td>
                                    <td>: <?php echo $db_username ?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;">: <?php echo $db_email ?></td>
                                </tr>
                                <tr>
                                    <td>Most Active In</td>
                                    <td>: <?php echo ucwords($most_active) . ' (' . ucwords(strtolower($active_catgroup)) . ')' ?></td>
                                </tr>
                                <tr id="last">
                                    <td>Number of Hearts</td>
                                    <td>: <?php echo $final_likes; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="posts">
                            <h6>Recent Posts On</h6>
                            <table>
                                <?php
                                $query_posts = "SELECT comment_id, cust_uname, post_title, created_at, post_id FROM ( SELECT comment_id as `comment_id`, posts.cust_uname as `cust_uname`, post_title as `post_title`, comment_createdAt as `created_at`, posts.post_id as `post_id` FROM comments join posts on posts.post_id = comments.post_id WHERE comments.cust_id = '{$db_userID}' UNION SELECT 0 as `comment_id`, cust_uname as `cust_uname`, post_title as `post_title`, created_at as `created_at`, posts.post_id as `post_id` FROM posts WHERE posts.cust_id = '{$db_userID}' ) results order by created_at desc limit 5";
                                $query_posts_go = mysqli_query($connection, $query_posts);
                                if (mysqli_num_rows($query_posts_go) > 0) {
                                    while ($row_posts = mysqli_fetch_assoc($query_posts_go)) {
                                        $db_commentID = $row_posts['comment_id'];
                                        $db_postUser = $row_posts['cust_uname'];
                                        $db_postTitle = $row_posts['post_title'];
                                        $db_postID = $row_posts['post_id'];
                                        $db_postCreatedAt = $row_posts['created_at'];
                                        $age_string = '';
                                        if (strtotime(date('Y-m-d H:i:s')) > strtotime($db_postCreatedAt)) {
                                            $comment_age = strtotime(date('Y-m-d H:i:s')) - strtotime($db_postCreatedAt);
                                        } else {
                                            $comment_age = 0;
                                        }
                                        if ($comment_age < 60) {
                                            $age_string = 'Moments ago';
                                        } else if ($comment_age >= 60 && $comment_age < 3600) {
                                            $post_age = $comment_age / 60;
                                            $age_string = (int) $post_age . ' minute ago';
                                        } else if ($comment_age >= 3600 && $comment_age < 86400) {
                                            $post_age = $comment_age / 3600;
                                            $age_string = (int) $post_age . ' hour ago';
                                        } else if ($comment_age >= 86400 && $comment_age / 86400 <= 30) {
                                            $post_age = $comment_age / 86400;
                                            $age_string = (int) $post_age . ' day ago';
                                        } else if ($comment_age > 2592000 && $comment_age / 2592000 <= 12) {
                                            $post_age = $comment_age / 2592000;
                                            $age_string = (int) $post_age . ' month ago';
                                        } else if ($comment_age > 31104000) {
                                            $post_age = $comment_age / 31104000;
                                            $age_string = (int) $post_age . ' year ago';
                                        }
                                ?>
                                        <tr>
                                            <td>
                                                <a href="./post.php?title=<?php echo $db_postID; ?>"><?php echo $db_postTitle; ?></a>
                                            </td>
                                            <td>by <?php echo $db_postUser ?></td>
                                            <td><?php echo $age_string ?></td>
                                        </tr>
                                <?php    }
                                } else {
                                    echo "This person hasn't made any post/comment yet";
                                } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="user-buttons">
            <ul>
                <a href="customer-profile-update.php"><button class="btn btn-primary"><?php echo "Update Profile"; ?></button></a>
                <a href="customer-billing-shipping-update.php"><button class="btn btn-primary"><?php echo "Update Address"; ?></button></a>
                <!-- <a href="customer-password-update.php"><button class="btn btn-danger"><?php echo "Update Password"; ?></button></a> -->
            </ul>
            <ul>
                <a href="logout.php"><button class="btn btn-danger"><?php echo "Logout"; ?></button></a>
            </ul>
        </div>
    </main>
<?php endif; ?>
<?php require_once('footer.php'); ?>