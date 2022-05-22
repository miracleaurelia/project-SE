<?php
include("database.php");
$connection = setUpDBConnection();

if (isset($_POST['but_upload'])) {
    $name = $_FILES['file']['name'];
    $target_dir = "../profile_pictures/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $extensions_arr = array("jpg", "jpeg", "png", "gif");

    if (in_array($imageFileType, $extensions_arr)) {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $name)) {
            $query = "INSERT INTO tbl_customer (photo) VALUES('" . $name . "')";
            mysqli_query($connection, $query);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post" action="cobaimg.php" enctype='multipart/form-data'>
        <input type='file' name='file' />
        <input type='submit' value='Save name' name='but_upload'>
    </form>
</body>
</html>