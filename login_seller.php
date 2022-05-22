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

  if (empty($_POST['seller_email']) || empty($_POST['seller_password'])) {
    $error_message = "Email and/or Password can not be empty." . '<br>';
  } else {

    $seller_email = strip_tags($_POST['seller_email']);
    $seller_password = strip_tags($_POST['seller_password']);

    $statement = $pdo->prepare("SELECT * FROM tbl_seller WHERE seller_email=?");
    $statement->execute(array($seller_email));
    $total = $statement->rowCount();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
      $seller_status = $row['seller_status'];
      $row_password = $row['seller_password'];
    }

    if ($total == 0) {
      $error_message .= "Email Address does not match." . '<br>';
    } else {
      if ($row_password != md5($seller_password)) {
        $error_message .= "Passwords do not match." . '<br>';
      } else {
        if ($seller_status == 0) {
          $error_message .= "Sorry! Your account is inactive. Please contact to the administrator." . '<br>';
        } else {
          $_SESSION['seller'] = $row;
          header("location: ./seller/index.php");
        }
      }
    }
  }
}
?>

<div class="page-banner" style="background-color:#444;background-image: url(assets/uploads/<?php echo $banner_login; ?>);">
  <div class="inner">
    <h3><?php echo "Seller Login"; ?></h3>
  </div>
</div>

<div class="page">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="user-content">


          <form action="" method="post">
            <?php $csrf->echoInputField(); ?>
            <div class="row">
              <div class="col-md-4"></div>
              <div class="col-md-4">
                <?php
                if ($error_message != '') {
                  echo "<div class='error' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $error_message . "</div>";
                }
                if ($success_message != '') {
                  echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $success_message . "</div>";
                }
                ?>
                <div class="form-group">
                  <label for=""><?php echo "Email Address"; ?> *</label>
                  <input type="email" class="form-control" name="seller_email">
                </div>
                <div class="form-group">
                  <label for=""><?php echo "Password"; ?> *</label>
                  <input type="password" class="form-control" name="seller_password">
                </div>
                <div class="form-group">
                  <label for=""></label>
                  <input type="submit" class="btn btn-success" value="<?php echo "Submit"; ?>" name="form1">
                </div>
                <div class="forget-pass">
                  <a href="./forget-password.php" style="color:#e4144d;"><?php echo "Forget Password"; ?>?</a>
                </div>
                <div class="to-regis">
                  <a href="./registration-seller.php" style="color:#e4144d;"><?php echo "Don't Have Account? Register"; ?></a>
                </div>

              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once('footer.php'); ?>