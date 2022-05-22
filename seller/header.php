<?php
ob_start();
session_start();
include("../admin/inc/config.php");
include("../admin/inc/functions.php");
include("../admin/inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();
$error_message = '';
$success_message = '';
$error_message1 = '';
$success_message1 = '';

if (!isset($_SESSION['seller'])) {
  header('location: login.php');
  exit;
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php if (isset($_SESSION['seller'])) {
            echo "Seller Panel";
          }
          ?></title>

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="stylesheet" href="../admin/css/bootstrap.min.css">
  <link rel="stylesheet" href="../admin/css/font-awesome.min.css">
  <link rel="stylesheet" href="../admin/css/ionicons.min.css">
  <link rel="stylesheet" href="../admin/css/datepicker3.css">
  <link rel="stylesheet" href="../admin/css/all.css">
  <link rel="stylesheet" href="../admin/css/select2.min.css">
  <link rel="stylesheet" href="../admin/css/dataTables.bootstrap.css">
  <link rel="stylesheet" href="../admin/css/jquery.fancybox.css">
  <link rel="stylesheet" href="../admin/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../admin/css/_all-skins.min.css">
  <link rel="stylesheet" href="../admin/css/on-off-switch.css" />
  <link rel="stylesheet" href="../admin/css/summernote.css">
  <link rel="stylesheet" href="../admin/style.css">

</head>
<?php if (isset($_SESSION['user'])) {
  echo "Admin Panel";
} else if (isset($_SESSION['seller'])) {
  echo "Seller Panel";
}
?>

<?php if (isset($_SESSION['seller'])) : ?>

  <body class="hold-transition fixed skin-blue sidebar-mini">

    <div class="wrapper">

      <header class="main-header">

        <a href="index.php" class="logo">
          <span class="logo-lg">GoToStore</span>
        </a>

        <nav class="navbar navbar-static-top">

          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>

          <span style="float:left;line-height:50px;color:#fff;padding-left:15px;font-size:18px;">Seller Page</span>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="../assets/uploads/<?= $_SESSION['seller']['seller_photo']; ?>" class="user-image" alt="Seller Image">
                  <span class="hidden-xs"><?= $_SESSION['seller']['seller_name']; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="user-footer">
                    <div>
                      <a href="profile-edit.php" class="btn btn-default btn-flat">Edit Profile</a>
                    </div>
                    <div>
                      <a href="logout.php" class="btn btn-default btn-flat">Log out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>

        </nav>
      </header>

      <?php $cur_page = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1); ?>

      <aside class="main-sidebar">
        <section class="sidebar">

          <ul class="sidebar-menu">

            <li class="treeview <?php if ($cur_page == 'index.php') {
                                  echo 'active';
                                } ?>">
              <a href="index.php">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
            </li>


            <li class="treeview <?php if (($cur_page == 'product.php') || ($cur_page == 'product-add.php') || ($cur_page == 'product-edit.php')) {
                                  echo 'active';
                                } ?>">
              <a href="./product.php">
                <i class="fa fa-shopping-bag"></i> <span>Product Management</span>
              </a>
            </li>


            <li class="treeview <?php if (($cur_page == 'order.php')) {
                                  echo 'active';
                                } ?>">
              <a href="order.php">
                <i class="fa fa-sticky-note"></i> <span>Order Management</span>
              </a>
            </li>

            <!-- <li class="treeview <?php if (($cur_page == 'subscriber.php') || ($cur_page == 'subscriber.php')) {
                                        echo 'active';
                                      } ?>">
              <a href="subscriber.php">
                <i class="fa fa-hand-o-right"></i> <span>Subscriber</span>
              </a>
            </li> -->

          </ul>
        </section>
      </aside>

      <div class="content-wrapper">
  </body>
<?php endif ?>