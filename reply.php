<?php session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");
require "database.php";
ini_set('date.timezone', 'Asia/Jakarta');
$connection = setUpDBConnection();
$valid = 0;

if (isset($_SESSION['customer'])) {
    $db_username = $_SESSION['customer']['cust_uname'];
    $db_email = $_SESSION['customer']['cust_email'];
    $query = "SELECT * FROM tbl_customer WHERE cust_uname = '{$db_username}' AND cust_email = '{$db_email}'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $db_postNum = $row['post_num'];
        $db_img = $row['photo'];
        $db_lastOnline = $row['cust_datetime'];
        $db_userID = $row['cust_id'];
        $cust_status = $row['cust_status'];
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
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./styles/reply.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
    <script src="./ckfinder/ckfinder.js"></script>
</head>

<body>
    <div class="thread-reply">
        <div class="profile-title-r">
            <h6>Creating Reply to Main Post</h6>
        </div>
        <div class="profile-content-r">
            <div class="profile-stats-r">
                <div class="profile-pic-r">
                    <div class="pic-wrap-r">
                        <?php if ($flagImg) : ?>
                            <img src="profile_pictures/default.png" alt="">
                        <?php endif; ?>
                    </div>
                    <h5><?php echo $db_username ?></h5>
                    <h6>
                        <?php if ($isOnline) : ?>
                            Online
                        <?php else : ?>
                            Offline
                        <?php endif; ?>
                    </h6>
                </div>
                <div class="detail-stats-r">
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
            <div class="post-content-details-r">
                <div class="post-content-r">
                    <form method="post" action="">
                        <textarea id="editor" name="editor"></textarea><br>
                        <input type="submit" name="submit" value="Submit" id="submit" style="display: none;">
                    </form>
                </div>
            </div>
        </div>
        <div class="post-actions-r">
            <div class="post-cancel">
                <i class="fa fa-times" aria-hidden="true"></i>
            </div>
            <div class="post-confirm">
                <i class="fas fa-check"></i>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        ClassicEditor
            .create(document.querySelector('#editor'), {
                mediaEmbed: {
                    previewsInData: true
                },
                ckfinder: {
                    uploadUrl: './ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
                }
            })
            .then(newEditor => {
                editor = newEditor;
            })
            .catch(error => {
                if (error) {
                    console.error(error);
                }
            });
    </script>
</body>

</html>