<?php session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");
require "database.php";
ini_set('date.timezone', 'Asia/Jakarta');
$connection = setUpDBConnection();

if (isset($_POST['action_comment']) && isset($_POST['comment_content'])) {
    $comment_fromPost = $_POST['action_comment'];
    $query_comment = "SELECT * FROM posts WHERE post_id = '{$comment_fromPost}'";
    $result_comment = mysqli_query($connection, $query_comment);
    if (mysqli_num_rows($result_comment) > 0) {
        $row_comment = mysqli_fetch_assoc($result_comment);
        $comment_postID = $row_comment['post_id'];
    }
    $comments = $_POST['comment_content'];
    $comments = mysqli_real_escape_string($connection, $comments);
    $db_username = $_SESSION['customer']['cust_uname'];
    $db_email = $_SESSION['customer']['cust_email'];
    $db_userID = $_SESSION['customer']['cust_id'];
    
    $query = "INSERT INTO comments(cust_id, post_id, reply_to, comment_createdAt, reply_content, likes) VALUES('{$db_userID}', '{$comment_postID}', 'main', now(), '{$comments}', 0)";
    $insert_comment = mysqli_query($connection, $query);
    $update_comment_count = "UPDATE posts SET comments = comments + 1 WHERE post_id = '{$comment_postID}'";
    $update_comment_count_go = mysqli_query($connection, $update_comment_count);

    $update_lastrep = "UPDATE posts SET last_reply = now() WHERE post_id = '{$comment_postID}'";
    $update_lastrep_go = mysqli_query($connection, $update_lastrep);

    $add_postNum = "UPDATE tbl_customer SET post_num = post_num + 1 WHERE cust_id = '{$db_userID}'";
    $add_go = mysqli_query($connection, $add_postNum);
}
?>