<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");
require_once('header.php');
require "database.php";

$connection = setUpDBConnection();
if(!isset($_SESSION['customer'])) {
    header('location: logout.php');
    exit;
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
</head>

<body>
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
                                            $verification_url = 'http://127.0.0.1/gotostore-php/verify.php?email='.$to.'&key='.$key;

                                            $message = 'Thanks for your interest to subscribe our newsletter!<br><br>
                                            Please click this link to confirm your subscription:
                                            '.$verification_url.'<br><br>
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
        function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
            return false;
        };
        var getTitle = getUrlParameter('title');
        var commentID = getUrlParameter('commentid');

        function fetch_thread(title) {
            $.ajax({
                type: 'post',
                url: "./threads.php",
                data: {
                    action: title
                },
                success: function(response) {
                    $('.post-thread').html(response);
                }
            });
        }

        function fetch_replies(postTitle) {
            $.ajax({
                type: 'post',
                url: "./comments.php",
                data: {
                    "postTitle": postTitle
                },
                success: function(response) {
                    $('.display-reply-section').html(response);
                }
            });
            let postData = postTitle;
            $(document).ajaxStop(function() {
                $(".fas.fa-reply.nonMain").off("click").on("click", function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    let idName = $(this).parents().eq(3).attr("id");
                    let splitted = idName.split("-");
                    let commentID = parseInt(splitted[1]);
                    $(".thread-reply").remove();
                    fetch_commentsNon_editor(commentID);
                    $(document).ajaxStop(function() {
                        $(".fas.fa-check").off("click").on("click", function(event) {
                            event.preventDefault();
                            event.stopPropagation();
                            var commentNon_content = editor.getData();
                            $.ajax({
                                type: "POST",
                                url: "./post_replyNon.php",
                                data: {
                                    "comment_content": commentNon_content,
                                    "action_comment": postData,
                                    "replyTo_ID": commentID
                                },
                                success: function(data) {
                                    alert("Successfully Sent");
                                    $(".thread-reply").remove();
                                }
                            });
                            fetch_replies(postData);
                        });
                        $(".fa.fa-times").off("click").on("click", function(event) {
                            event.preventDefault();
                            event.stopPropagation();
                            $(".thread-reply").remove();
                        });
                    });
                });
                $(".far.fa-heart.nonMain").off("click").on("click", function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    var this_heart = $(this);
                    let idName = $(this).parents().eq(3).attr("id");
                    let splitted = idName.split("-");
                    let commentID = parseInt(splitted[1]);
                    var likeAmount = parseInt($(this_heart.parent().parent().prev().children().children()).html());
                    if ($(this_heart).hasClass('liked')) {
                        $.ajax({
                            type: "POST",
                            url: "./remove-like.php",
                            data: {
                                "commentOrPost_id": commentID,
                                "commentOrPost": "comment"
                            },
                            success: function(data) {
                                $(this_heart).removeClass("liked");
                                likeAmount--;
                                $(this_heart.parent().parent().prev().children().children()).html(likeAmount);
                            }
                        });
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "./add-like.php",
                            data: {
                                "commentOrPost_id": commentID,
                                "commentOrPost": "comment"
                            },
                            success: function(data) {
                                $(this_heart).addClass("liked");
                                likeAmount++;
                                $(this_heart.parent().parent().prev().children().children()).html(likeAmount);
                            }
                        });
                    }
                });
                $(".fas.fa-trash").off("click").on("click", function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    let idName = $(this).parents().eq(3).attr("id");
                    let splitted = idName.split("-");
                    let commentID = parseInt(splitted[1]);
                    if (confirm('Continue deletion of comment?')) {
                        $.ajax({
                            type: "POST",
                            url: "./remove-comment.php",
                            data: {
                                "comment_id": commentID
                            },
                            success: function(data) {
                                alert("Deletion of comment completed");
                            }
                        });
                        fetch_replies(postData);
                    } else {
                        alert('Deletion canceled.');
                    }
                });
            });
        }

        function fetch_comments_editor(id) {
            $.ajax({
                type: 'post',
                url: "./reply.php",
                success: function(response) {
                    $('#thread-' + id + '.thread').after(response);
                }
            });
        }

        function fetch_commentsNon_editor(id) {
            $.ajax({
                type: 'post',
                url: "./reply_nonmain.php",
                success: function(response) {
                    $('#comment-' + id + '.comment').after(response);
                }
            });
        }

        if (getTitle) {
            postData = parseInt(getTitle);
            fetch_thread(getTitle);
            fetch_replies(getTitle);
            if (commentID) {
                var once = true;
                $(document).ready(function() {
                    $(document).ajaxStop(function() {
                        $("#" + commentID + ".comment .profile-title").css("background-color", "red");
                        if (once) {
                            $('html, body').stop(true, false).animate({
                                scrollTop: $("#" + commentID + ".comment").offset().top
                            }, 1000);
                            once = false;
                        }
                    });
                });
            }
            $(document).ajaxStop(function() {
                $("#deleteThreadBtn").off("click").on("click", function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    let idName = $(this).parent().parent().attr("id");
                    let splitted = idName.split("-");
                    let delThreadID = parseInt(splitted[1]);
                    if (confirm('Continue deletion of thread?')) {
                        $.ajax({
                            type: "POST",
                            url: "./remove-thread.php",
                            data: {
                                "thread_id": delThreadID
                            },
                            success: function(data) {
                                alert("Deletion of thread completed");
                                window.location = "./select.php";
                            }
                        });
                    } else {
                        alert('Deletion of thread canceled.');
                    }
                });
                $(".fas.fa-reply.main").off("click").on("click", function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    let idName = $(this).parents().eq(3).attr("id");
                    let splitted = idName.split("-");
                    let threadID = parseInt(splitted[1]);
                    $(".thread-reply").remove();
                    fetch_comments_editor(threadID);
                    $(document).ajaxStop(function() {
                        $(".fas.fa-check").off("click").on("click", function(event) {
                            event.preventDefault();
                            event.stopPropagation();
                            var comment_contentt = editor.getData();
                            $.ajax({
                                type: "POST",
                                url: "./post_reply.php",
                                data: {
                                    "comment_content": comment_contentt,
                                    "action_comment": postData
                                },
                                success: function(data) {
                                    alert("Successfully Sent");
                                    $(".thread-reply").remove();
                                }
                            });
                            fetch_replies(postData);
                        });
                        $(".fa.fa-times").off("click").on("click", function(event) {
                            event.preventDefault();
                            event.stopPropagation();
                            $(".thread-reply").remove();
                        });
                    });
                });
                $(".far.fa-heart.main").off("click").on("click", function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    var thiss = $(this);
                    let idName = $(this).parents().eq(3).attr("id");
                    let splitted = idName.split("-");
                    let threadID = parseInt(splitted[1]);
                    var likeAmount = parseInt($(thiss.parent().parent().prev().children().children()).html());
                    if ($(thiss).hasClass('liked')) {
                        $.ajax({
                            type: "POST",
                            url: "./remove-like.php",
                            data: {
                                "commentOrPost_id": threadID,
                                "commentOrPost": "post"
                            },
                            success: function(data) {
                                $(thiss).removeClass("liked");
                                likeAmount--;
                                $(thiss.parent().parent().prev().children().children()).html(likeAmount);
                            }
                        });
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "./add-like.php",
                            data: {
                                "commentOrPost_id": threadID,
                                "commentOrPost": "post"
                            },
                            success: function(data) {
                                $(thiss).addClass("liked");
                                likeAmount++;
                                $(thiss.parent().parent().prev().children().children()).html(likeAmount);
                            }
                        });
                    }
                });
            });
        }

        function renew_session(number) {
            $.ajax({
                type: 'post',
                url: 'session.php',
                data: {
                    action: number
                },
                success: function(response) {
                }
            });
        }
        setInterval(function() {
            renew_session(2);
        }, 1000);
    </script>
</body>

</html>