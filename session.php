<?php session_start(); 
require "database.php";

$connection = setUpDBConnection();

function destroy_session_auto() {
    global $connection;
    $db_username = $_SESSION['customer']['cust_uname'];
    $db_email = $_SESSION['customer']['cust_email'];
    $query = "SELECT cust_datetime FROM tbl_customer WHERE cust_uname = '{$db_username}' AND cust_email = '{$db_email}'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_updated = $row['cust_datetime'];
        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');
        $time_now = strtotime($now);
        $time_updated = strtotime("+3 minutes", strtotime($last_updated));
        if ($time_now > $time_updated) {
            session_unset();
            session_destroy();
            session_write_close();
            setcookie(session_name(),'',0,'/');
        }
    }
}

function renew_session() {
    global $connection;
    $db_username = $_SESSION['customer']['cust_uname'];
    $db_email = $_SESSION['customer']['cust_email'];
    $renew_query = "UPDATE tbl_customer SET cust_datetime = NOW() WHERE cust_uname = '{$db_username}' AND cust_email = '{$db_email}'";
    $now = date('Y-m-d H:i:s');
    // $time_updated = strtotime("+3 minutes", strtotime($now));
    // $_SESSION['expire'] = $time_updated;
    mysqli_query($connection, $renew_query);
}

if ($_POST['action'] == 1) {
    destroy_session_auto();
}
else if ($_POST['action'] == 2) {
    renew_session();
    echo "<script>console.log('hello');</script>";
}
?>