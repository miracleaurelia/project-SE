<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");
require_once('header.php');
require "database.php";

$connection = setUpDBConnection();
if (!isset($_SESSION['customer'])) {
    header('location: logout.php');
    exit;
}
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $banner_product_category = $row['banner_product_category'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="./styles/style.css">
    <style>
        .pages {
            display: inline-block;
            margin: 1rem 0;
        }

        .page_current {
            display: inline-block;
            position: relative;
            width: 40px;
            height: 40px;
            line-height: 40px;
            cursor: pointer;
            text-align: center;
            margin-right: 16px;
            background: #fe4c50;
            border: none;
        }

        .page_current span {
            color: #FFFFFF;
        }

        .page_selection {
            display: none;
            position: absolute;
            right: 0;
            top: 120%;
            margin: 0;
            width: 100%;
            background: #FFFFFF;
            visibility: hidden;
            opacity: 0;
            z-index: 1;
            box-shadow: 0 15px 25px rgba(63, 78, 100, 0.15);
            -webkit-transition: opacity 0.3s ease;
            -moz-transition: opacity 0.3s ease;
            -ms-transition: opacity 0.3s ease;
            -o-transition: opacity 0.3s ease;
            transition: all 0.3s ease;
            padding: 0;
        }

        .page_selection li {
            display: block;
            text-align: center;
            padding-left: 10px;
            padding-right: 10px;
        }

        .page_selection li a {
            display: block;
            height: 40px;
            line-height: 40px;
            border-bottom: solid 1px #dddddd;
            color: #51545f;
            -webkit-transition: opacity 0.3s ease;
            -moz-transition: opacity 0.3s ease;
            -ms-transition: opacity 0.3s ease;
            -o-transition: opacity 0.3s ease;
            transition: all 0.3s ease;
        }

        .page_selection li a:hover {
            color: #b5aec4;
        }

        .page_current:hover .page_selection {
            visibility: visible;
            opacity: 1;
            top: calc(100% + 1px);
            display: block;
        }

        .page_total {
            display: inline-block;
            line-height: 40px;
            margin-right: 31px;
        }

        .page_total span {
            margin-right: 14px;
        }

        .page_next,
        .page_prev {
            display: inline-block;
            line-height: 40px;
            cursor: pointer;
        }

        .page_next:hover i,
        .page_prev:hover i {
            color: #b5aec4;
        }

        .page_next i,
        .page_prev i {
            font-size: 18px;
            color: #51545f;
        }

        .page_prev {
            margin-right: 31px;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
</head>

<body>
    <div class="page-banner">
        <div class="inner">
            <h3 style="color: black;">Discussion Forum</h3>
        </div>
    </div>

    <div class="forum">
        <div class="add-button">
            <a href="./add-thread.php" id="addBtn">Add New Post</a>
        </div>
        <h3 style="padding: 0.5rem 2rem;">Category</h3>
        <div id="select_box">

            <ul class="select">
                <?php
                $host = 'localhost';
                $user = 'root';
                $pass = '';
                $database = "ecommerceweb";
                $connection = mysqli_connect($host, $user, $pass, $database);
                $query = "select tcat_name, tcat_id from tbl_top_category";
                $select = mysqli_query($connection, $query);
                while ($row = mysqli_fetch_assoc($select)) {
                    echo "<li id='tcat-" . $row['tcat_id'] . "'>" . $row['tcat_name'] . "</li>";
                }
                ?>
            </ul>

            <div id="new_select">

            </div>

            <div id="posts" style="display: none;">
                <div class="post-ui">

                </div>
            </div>
        </div>
    </div>

    <div class="post-thread">

    </div>

    <div class="display-reply-section">

    </div>

    <div class="thread-reply-section">

    </div>

    <?php
    // require "database.php";
    $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $footer_about = $row['footer_about'];
        $contact_email = $row['contact_email'];
        $contact_phone = $row['contact_phone'];
        $contact_address = $row['contact_address'];
        $footer_copyright = $row['footer_copyright'];
        $total_recent_post_footer = $row['total_recent_post_footer'];
        $total_popular_post_footer = $row['total_popular_post_footer'];
        $newsletter_on_off = $row['newsletter_on_off'];
        $before_body = $row['before_body'];
    }
    ?>


    <?php if ($newsletter_on_off == 1) : ?>
        <section class="home-newsletter" style="margin-top: 1rem;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-md-offset-3">
                        <div class="single">
                            <?php
                            if (isset($_POST['form_subscribe'])) {

                                if (empty($_POST['email_subscribe'])) {
                                    $valid = 0;
                                    $error_message1 .= "Email Address can not be empty";
                                } else {
                                    if (filter_var($_POST['email_subscribe'], FILTER_VALIDATE_EMAIL) === false) {
                                        $valid = 0;
                                        $error_message1 .= "Email address must be valid. ";
                                    } else {
                                        $statement = $pdo->prepare("SELECT * FROM tbl_subscriber WHERE subs_email=?");
                                        $statement->execute(array($_POST['email_subscribe']));
                                        $total = $statement->rowCount();
                                        if ($total) {
                                            $valid = 0;
                                            $error_message1 .= "Email Address Already Exists.";
                                        } else {
                                            $key = md5(uniqid(rand(), true));
                                            $current_date = date('Y-m-d');
                                            $current_date_time = date('Y-m-d H:i:s');
                                            $statement = $pdo->prepare("INSERT INTO tbl_subscriber (subs_email,subs_date,subs_date_time,subs_hash,subs_active) VALUES (?,?,?,?,?)");
                                            $statement->execute(array($_POST['email_subscribe'], $current_date, $current_date_time, $key, 0));
                                            ini_set("SMTP", "smtp.gmail.com");
                                            ini_set("sendmail_from", "miracleaureliarec@gmail.com");
                                            ini_set("smtp_port", 465);
                                            ini_set("username", "miracleaureliarec@gmail.com");
                                            ini_set("password", "ThisIsStrongPass255");

                                            $to = $_POST['email_subscribe'];
                                            $subject = 'Subscriber Email Confirmation';
                                            $verification_url = 'http://127.0.0.1/gotostore-php/verify.php?email=' . $to . '&key=' . $key;

                                            $message = 'Thanks for your interest to subscribe our newsletter!<br><br>
                                            Please click this link to confirm your subscription:
                                            ' . $verification_url . '<br><br>
                                            This link will be active only for 24 hours.';

                                            $headers = "MIME-Version: 1.0" . "\r\n";
                                            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                                            $headers .= "From: miracleaureliarec@gmail.com";

                                            if (mail($to, $subject, $message, $headers)) {
                                                $success_message1 = "Please check your email and confirm your subscription.";
                                            }
                                        }
                                    }
                                }
                            }
                            if ($error_message1 != '') {
                                echo "<script>alert('" . $error_message1 . "')</script>";
                            }
                            if ($success_message1 != '') {
                                echo "<script>alert('" . $success_message1 . "')</script>";
                            }
                            ?>
                            <form action="" method="post">
                                <?php $csrf->echoInputField(); ?>
                                <h2><?php echo "Subscribe To Our Newsletter"; ?></h2>
                                <div class="input-group">
                                    <input type="email" class="form-control" placeholder="<?php echo "Enter Your Email Address"; ?>" name="email_subscribe">
                                    <span class="input-group-btn">
                                        <button class="btn btn-theme" type="submit" name="form_subscribe"><?php echo "Subscribe"; ?></button>
                                    </span>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>




    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-12 copyright">
                    <?php echo $footer_copyright; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        var cur_page = 1;
        var cur_cat = 1;

        function fetch_select(val) {
            $.ajax({
                type: 'post',
                url: 'fetch_data.php',
                data: {
                    get_option: val
                },
                success: function(response) {
                    console.log(response);
                    document.getElementById("new_select").innerHTML = response;
                }
            });
        }

        function fetch_table(tableData, page) {
            $.ajax({
                type: 'post',
                url: 'fetch_table.php',
                data: {
                    get_option: tableData,
                    "page": page
                },
                success: function(response) {
                    console.log(response);
                    $(".post-ui").html(response);
                }
            });
        }

        $(document).ready(function() {
            fetch_select(1);
            $(".select li:nth-of-type(1)").addClass("active");
            $("#posts").css("display", "block");
            fetch_table(cur_cat, 1);
        });

        $(".select li").off().on("click", function() {
            $(".select li.active").removeClass("active");
            $(this).addClass("active");
            let content = $(this).attr("id");
            let splitted = content.split("-");
            content = parseInt(splitted[1]);
            fetch_select(content);
        });
        $(document).ajaxStop(function() {
            $("#new_select table tbody tr").off().on("click", function() {
                cur_page = 1;
                $("#new_select table tbody tr.active").removeClass("active");
                $(this).addClass("active");
                $("#posts").css("display", "block");
                var tableData = $(this).find('.category-name').attr("id");
                var split = tableData.split("-");
                tableData = parseInt(split[1]);
                fetch_table(tableData, 1);
                cur_cat = tableData;
            });
            $(".page_next").off().on("click", function() {
                cur_page++;
                fetch_table(cur_cat, cur_page);
            });
            $(".page_prev").off().on("click", function() {
                cur_page--;
                fetch_table(cur_cat, cur_page);
            });
            $(".page_selection li").off().on("click", function() {
                cur_page = parseInt($(this).text());
                fetch_table(cur_cat, cur_page);
            });
        });
        $(document).ajaxStop(function() {
            $(".post-title").off().on("click", function() {
                var postData = $(this).parent().attr("id");
                var split = postData.split("-");
                postData = parseInt(split[1]);
                window.location = './post.php?title=' + postData;
            });
        });

        function renew_session(number) {
            $.ajax({
                type: 'post',
                url: 'session.php',
                data: {
                    action: number
                },
                success: function(response) {}
            });
        }
        setInterval(function() {
            renew_session(2);
        }, 1000);
    </script>
</body>

</html>