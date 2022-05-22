<?php session_start();
if(isset($_POST['comment_id']))
{
    $host = "localhost";
    $user = "root";
    $pass = "";
    $database = "ecommerceweb";
    $connection = mysqli_connect($host, $user, $pass, $database);
    $commentID = $_POST['comment_id'];
    $get_post_query = "SELECT post_id FROM comments WHERE comment_id = '{$commentID}'";
    $get_post = mysqli_query($connection, $get_post_query);

    $res = mysqli_fetch_assoc($get_post);
    $post_id = $res['post_id'];

    $query = "DELETE FROM comments WHERE comment_id = '{$commentID}'";
    $go = mysqli_query($connection, $query);

    $minus_comment = "UPDATE posts SET comments = comments - 1 WHERE post_id = '{$post_id}'";
    $minus_comment_go = mysqli_query($connection, $minus_comment);

    $minus_comment2 = "DELETE FROM tbl_likes WHERE commentOrPost = 'comment' AND id = '{$commentID}'";
    $minus_comment_go2 = mysqli_query($connection, $minus_comment2);

    $cust_id = $_SESSION['customer']['cust_id'];
    $minus = "UPDATE tbl_customer SET post_num = post_num - 1 WHERE cust_id = '{$cust_id}'";
    $minus_go = mysqli_query($connection, $minus);
}
?>