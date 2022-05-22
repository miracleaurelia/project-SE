<?php
ini_set('date.timezone', 'Asia/Jakarta');

if(isset($_POST['get_option']) && isset($_POST['page'])) {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $database = "ecommerceweb";
    $limit = 5;
    $page = ($_POST['page'] - 1) * $limit;
    $connection = mysqli_connect($host, $user, $pass, $database);
    $categoryName = $_POST['get_option'];
    // $categoryName = mysqli_real_escape_string($connection, $categoryName);
    // $categoryName = htmlspecialchars_decode($categoryName);
    $get_total = "select * from posts where mcat_id='$categoryName'";
    $get_total_go = mysqli_query($connection, $get_total);
    $total_post = mysqli_num_rows($get_total_go);
    $total_pages = ceil($total_post / $limit);
    $query= "select * from posts where mcat_id='$categoryName' order by hot desc, cast(((views+(comments*10))/(timestampdiff(second, created_at, now()))) as float) desc limit $limit offset $page";
    $find = mysqli_query($connection, $query);
    echo '<table class="table">';
    if (mysqli_num_rows($find) > 0) {
        while ($row=mysqli_fetch_assoc($find)) {
            echo "<tr id='viewthread-" . $row['post_id'] . "'>";
            if (strtotime(date('Y-m-d H:i:s')) > strtotime($row['last_reply'])) {
                $last_reply_age = strtotime(date('Y-m-d H:i:s')) - strtotime($row['last_reply']);
            }
            else {
                $last_reply_age = 0;
            }
            $query_hot = "SELECT * FROM posts join comments on posts.post_id = comments.post_id where posts.post_id = '{$row['post_id']}' and comment_createdAt between now() - INTERVAL 5 MINUTE and now()";
            $find_hot = mysqli_query($connection, $query_hot);
            $isHot = 0;
            if (mysqli_num_rows($find_hot) > 10) {
                $isHot = 1;
                $update_hot = "UPDATE posts SET hot = 1 WHERE posts.post_id = '{$row['post_id']}'";
                $update_hot_go = mysqli_query($connection, $update_hot);
            }
            else {
                $update_hot = "UPDATE posts SET hot = 0 WHERE posts.post_id = '{$row['post_id']}'";
                $update_hot_go = mysqli_query($connection, $update_hot);
            }
            if ($isHot == 0) {
                echo '<td class="hot">' . ' ' . '</td>';
            }
            else {
                echo '<td class="hot">' . '[HOT]' . '</td>';
            }
            echo '<td class="post-title">' . $row['post_title'] . '</td>';
            echo '<td class="post-user">by ' . $row['cust_uname'] . '</td>';
            echo '<td class="post-views"><i class="fas fa-eye"></i>' . $row['views'] . '</td>';
            echo '<td class="post-comments"><i class="far fa-comment-alt"> </i>' . $row['comments'] . '</td>';
            if ($last_reply_age < 60) {
                echo '<td class="post-age">' . 'Moments ago' . '</td>';
            }
            else if ($last_reply_age >= 60 && $last_reply_age < 3600) {
                $age = $last_reply_age / 60;
                echo '<td class="post-age">' . (int) $age . 'm ago</td>';
            }
            else if ($last_reply_age>= 3600 && $last_reply_age < 86400) {
                $age = $last_reply_age / 3600;
                echo '<td class="post-age">' . (int) $age . ' hour ago</td>';
            }
            else if ($last_reply_age >= 86400 && $last_reply_age / 86400 <= 30) {
                $age = $last_reply_age / 86400;
                echo '<td class="post-age">' . (int) $age . ' day ago</td>';
            }
            else if ($last_reply_age > 2592000 && $last_reply_age / 2592000 <= 12) {
                $age = $last_reply_age / 2592000;
                echo '<td class="post-age">' . (int) $age . ' month ago</td>';
            }
            else if ($last_reply_age > 31104000) {
                $age = $last_reply_age / 31104000;
                echo '<td class="post-age">' . (int) $age . ' year ago</td>';
            }
            echo "</tr>";
        }
        echo "</table>";
        $arrow_right = '';
        $arrow_left = '';
        if ($_POST['page'] < $total_pages) {
            $arrow_right = '<div class="page_next"><a href="#"><i class="fas fa-arrow-right" aria-hidden="true"></i></a></div>';
        }
        if ($_POST['page'] > 1) {
            $arrow_left = '<div class="page_prev"><a href="#"><i class="fas fa-arrow-left" aria-hidden="true"></i></a></div>';
        }
        $i = 1;
        $li = '<li><a href="#">' . $i . '</a></li>';
        $i++;
        while ($i <= $total_pages) {
            $li .= '<li><a href="#">' . $i . '</a></li>';
            $i++;
        }
        echo '<div class="pages d-flex flex-row align-items-center">'
        . $arrow_left .
        '<div class="page_current">
        <span>' . $_POST['page'] . '</span>
            <ul class="page_selection">'
                . $li .
            '</ul>
        </div>
        <div class="page_total"><span>of</span>' . $total_pages . '</div>' . $arrow_right .
    '</div>';
    }
    else {
        echo "<p style='margin-left: 1rem;'>No posts yet</p>";
    }
    exit;
}
?>