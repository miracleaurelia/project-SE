<?php session_start();

$host = "localhost";
$user = "root";
$pass = "";
$database = "ecommerceweb";
$connection = mysqli_connect($host, $user, $pass, $database);
$tcatid = 0;

if (isset($_POST['tcatid'])) {
   $tcatid = mysqli_real_escape_string($connection, $_POST['tcatid']);
}

$mcat_arr = array();

if ($tcatid > 0) {
   $sql = "SELECT mcat_id, mcat_name FROM tbl_mid_category WHERE tcat_id=" . $tcatid;
   $result = mysqli_query($connection, $sql);

   while ($row = mysqli_fetch_array($result)) {
      $mcat_id = $row['mcat_id'];
      $mcat_name = $row['mcat_name'];
      $mcat_arr[] = array("id" => $mcat_id, "name" => $mcat_name);
   }
}
echo json_encode($mcat_arr);
?>