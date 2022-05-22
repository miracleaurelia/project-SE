<?php session_start();
if(isset($_POST['thread_id']))
{
    $host = "localhost";
    $user = "root";
    $pass = "";
    $database = "ecommerceweb";
    $connection = mysqli_connect($host, $user, $pass, $database);
    $threadID = $_POST['thread_id'];

    $del_postNum = "SELECT posts.post_id, comments.cust_id as `user_id`, count(comments.comment_id) as `total` FROM comments JOIN posts ON comments.post_id = posts.post_id WHERE posts.post_id = '{$threadID}' GROUP BY comments.cust_id, posts.post_id";
    $del_go = mysqli_query($connection, $del_postNum);

    if (mysqli_num_rows($del_go) > 0) {
        while ($row=mysqli_fetch_assoc($del_go)) {
            $user_id = $row['user_id'];
            $total_comments = $row['total'];
            $exec_del = "UPDATE tbl_customer SET post_num = post_num - '{$total_comments}' WHERE cust_id = '{$user_id}'";
            $exec_del_go = mysqli_query($connection, $exec_del);
        }
    }

    $query = "DELETE FROM posts WHERE post_id = '{$threadID}'";
    $go = mysqli_query($connection, $query);
    
    $cust_id = $_SESSION['customer']['cust_id'];
    $minus = "UPDATE tbl_customer SET post_num = post_num - 1 WHERE cust_id = '{$cust_id}'";
    $minus_go = mysqli_query($connection, $minus);
}
?>