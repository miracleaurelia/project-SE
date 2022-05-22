<?php require_once('header.php'); ?>
<!-- fetching row banner login -->
<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $banner_login = $row['banner_login'];
}
?>
<!-- login form -->
<?php
if (isset($_POST['form1'])) {

    if (empty($_POST['cust_email']) || empty($_POST['cust_password'])) {
        $error_message = "Email and/or Password can not be empty." . '<br>';
    } else {

        $cust_email = strip_tags($_POST['cust_email']);
        $cust_password = strip_tags($_POST['cust_password']);

        $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
        $statement->execute(array($cust_email));
        $total = $statement->rowCount();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $cust_status = $row['cust_status'];
            $row_password = $row['cust_password'];
        }

        if ($total == 0) {
            $error_message .= "Email Address does not match." . '<br>';
        } else {
            if ($row_password != md5($cust_password)) {
                $error_message .= "Passwords do not match." . '<br>';
            } else {
                if ($cust_status == 0) {
                    $error_message .= "Sorry! Your account is inactive. Please contact to the administrator." . '<br>';
                } else {
                    $_SESSION['customer'] = $row;
                    header("location: index.php");
                }
            }
        }
    }
}
?>

<div class="page-banner" style="background-color:#444;background-image: url(assets/uploads/<?php echo $banner_login; ?>);">
    <div class="inner">
        <h3><?php echo "Choose Login Type"; ?></h3>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row justify-content-center">
            <a class="text-light flex-fill col-lg-5 bg-danger text-center p-2 border rounded mx-2" href="./login_customer.php"><?= "Login as a Customer" ?></a>
            <a class="text-light flex-fill col-lg-5 bg-danger text-center p-2 border rounded mx-2" href="./login_seller.php"><?= "Login as a Seller" ?></a>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>