<?php session_start();
if(isset($_POST['commentOrPost_id']) && isset($_POST['commentOrPost']))
{
    $host = "localhost";
    $user = "root";
    $pass = "";
    $database = "ecommerceweb";
    $connection = mysqli_connect($host, $user, $pass, $database);
    $commentOrPostID = $_POST['commentOrPost_id'];
    $commentOrPost = $_POST['commentOrPost'];
    if ($commentOrPost == "comment") {
        $query = "UPDATE comments SET likes = likes + 1 WHERE comment_id = '{$commentOrPostID}'";
    }
    else {
        $query = "UPDATE posts SET likes = likes + 1 WHERE post_id = '{$commentOrPostID}'";
    }
    $update_like = mysqli_query($connection, $query);

    $cust_id = $_SESSION['customer']['cust_id'];

    $query_tbl_like = "INSERT INTO tbl_likes(commentOrPost, id, cust_id) VALUES('{$commentOrPost}', '{$commentOrPostID}', '{$cust_id}')";
    $update_tbl_like = mysqli_query($connection, $query_tbl_like);
}
?>