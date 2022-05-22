<?php
if(isset($_POST['get_option']))
{
    $host = "localhost";
    $user = "root";
    $pass = "";
    $database = "ecommerceweb";
    $connection = mysqli_connect($host, $user, $pass, $database);
    $categoryGroupId = $_POST['get_option'];
    // $categoryGroupName = mysqli_real_escape_string($connection, $categoryGroupName);
    // $categoryGroupName = htmlspecialchars_decode($categoryGroupName);
    $query= "select * from tbl_mid_category join tbl_top_category on tbl_mid_category.tcat_id = tbl_top_category.tcat_id where tbl_mid_category.tcat_id='$categoryGroupId'";
    $find = mysqli_query($connection, $query);
    echo '<table class="table">';
    while($row=mysqli_fetch_assoc($find)) {
        if ($row['mcat_name'] == 'Men Accessories') {
            echo '<tr class="active">';
            echo '<td class="category-name" id="mcat-' . $row['mcat_id'] . '">'. $row['mcat_name'] . '</td>';
            echo "</tr>";
        }
        else {
            echo "<tr>";
            echo '<td class="category-name" id="mcat-' . $row['mcat_id'] . '">' . $row['mcat_name'] . '</td>';
            echo "</tr>"; 
        }
    }
    echo "</table>";
    exit;
}
?>