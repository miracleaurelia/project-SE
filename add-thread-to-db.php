<?php session_start();
if(isset($_POST['posttcat']) && isset($_POST['postmcat']) && isset($_POST['posttitle']) 
&& isset($_POST['postContent']) && isset($_SESSION['customer']))
{
    $host = "localhost";
    $user = "root";
    $pass = "";
    $database = "ecommerceweb";
    $connection = mysqli_connect($host, $user, $pass, $database);
    $posttcat = $_POST['posttcat'];
    $postmcat = $_POST['postmcat'];
    $posttitle = $_POST['posttitle'];
    $posttitle = mysqli_real_escape_string($connection, $posttitle);
    $postContent = $_POST['postContent'];
    $postContent = mysqli_real_escape_string($connection, $postContent);
    $cust_id = $_SESSION['customer']['cust_id'];
    $cust_uname = $_SESSION['customer']['cust_uname'];

    $search = "SELECT mcat_name FROM tbl_mid_category WHERE mcat_id = '{$postmcat}'";
    $search_res = mysqli_query($connection, $search);
    $row_res = mysqli_fetch_assoc($search_res);
    $mcat_name = $row_res['mcat_name'];
    $mcat_name = mysqli_real_escape_string($connection, $mcat_name);
    $mcat_name = htmlspecialchars_decode($mcat_name);

    $query = "INSERT INTO posts(cust_uname, post_title, post_content, created_at, mcat_id, views, comments, hot, how_old, last_reply, likes, cust_id, mcat_name) VALUES('{$cust_uname}', '{$posttitle}', '{$postContent}', now(), '{$postmcat}', 0, 0, 0, 0, now(), 0, '{$cust_id}', '{$mcat_name}')";
    $go = mysqli_query($connection, $query);

    echo mysqli_insert_id($connection);

    $add_postNum = "UPDATE tbl_customer SET post_num = post_num + 1 WHERE cust_id = '{$cust_id}'";
    $add_go = mysqli_query($connection, $add_postNum);
}
?>