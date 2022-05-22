<?php require_once('header.php'); ?>

<section class="content-header">
  <h1>Dashboard</h1>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_top_category");
$statement->execute();
$total_top_category = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_mid_category");
$statement->execute();
$total_mid_category = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_end_category");
$statement->execute();
$total_end_category = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE SellerID = ?");
$statement->execute(array($_SESSION['seller']['id']));
$total_product = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_status='1'");
$statement->execute();
$total_customers = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_subscriber WHERE subs_active='1'");
$statement->execute();
$total_subscriber = $statement->rowCount();

// $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=? AND shipping_status = 'Completed' AND SellerID=?");
$statement = $pdo->prepare("SELECT 
tp.payment_id,tp.payment_method,tp.payment_status,tp.payment_date,tp.shipping_status
FROM `tbl_payment` tp JOIN tbl_order t_o
ON t_o.payment_id = tp.payment_id JOIN tbl_customer tc
ON tc.cust_id = tp.customer_id JOIN tbl_product tpr 
ON tpr.p_id = t_o.product_id
WHERE tp.payment_status = ? AND tpr.SellerID = ? 
GROUP BY tp.payment_id
ORDER BY tp.payment_id DESC");
$statement->execute(array('Completed', $_SESSION['seller']['id']));
$total_order_completed = $statement->rowCount();

// $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE shipping_status=? AND SellerID=?");
$statement = $pdo->prepare("SELECT 
tp.payment_id,tp.payment_method,tp.payment_status,tp.payment_date,tp.shipping_status
FROM `tbl_payment` tp JOIN tbl_order t_o
ON t_o.payment_id = tp.payment_id JOIN tbl_customer tc
ON tc.cust_id = tp.customer_id JOIN tbl_product tpr 
ON tpr.p_id = t_o.product_id
WHERE tp.shipping_status = ? AND tpr.SellerID = ? 
GROUP BY tp.payment_id
ORDER BY tp.payment_id DESC");
$statement->execute(array('Completed', $_SESSION['seller']['id']));
$total_shipping_completed = $statement->rowCount();

// $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=? AND shipping_status = ? AND SellerID= ? ");
$statement = $pdo->prepare("SELECT 
tp.payment_id,tp.payment_method,tp.payment_status,tp.payment_date,tp.shipping_status
FROM `tbl_payment` tp JOIN tbl_order t_o
ON t_o.payment_id = tp.payment_id JOIN tbl_customer tc
ON tc.cust_id = tp.customer_id JOIN tbl_product tpr 
ON tpr.p_id = t_o.product_id
WHERE tp.payment_status = ? AND tp.shipping_status= ? AND tpr.SellerID = ? 
GROUP BY tp.payment_id
ORDER BY tp.payment_id DESC");
$statement->execute(array('Completed', 'Pending', $_SESSION['seller']['id']));
$total_order_pending = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE SellerID= ?");
$statement = $pdo->prepare("SELECT 
tp.payment_id,tp.payment_method,tp.payment_status,tp.payment_date,tp.shipping_status
FROM `tbl_payment` tp JOIN tbl_order t_o
ON t_o.payment_id = tp.payment_id JOIN tbl_customer tc
ON tc.cust_id = tp.customer_id JOIN tbl_product tpr 
ON tpr.p_id = t_o.product_id
WHERE tp.shipping_status= ? AND tpr.SellerID = ? 
GROUP BY tp.payment_id
ORDER BY tp.payment_id DESC");
$statement->execute(array('Pending', $_SESSION['seller']['id']));
$total_order_complete_shipping_pending = $statement->rowCount();
?>

<section class="content">
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-primary">
        <div class="inner">
          <h3><?php echo $total_product; ?></h3>

          <p>Products</p>
        </div>
        <div class="icon">
          <i class="ionicons ion-android-cart"></i>
        </div>

      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-maroon">
        <div class="inner">
          <h3><?php echo $total_order_complete_shipping_pending; ?></h3>

          <p>Orders</p>
        </div>
        <div class="icon">
          <i class="ionicons ion-clipboard"></i>
        </div>

      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $total_order_completed; ?></h3>

          <p>Completed Orders</p>
        </div>
        <div class="icon">
          <i class="ionicons ion-android-checkbox-outline"></i>
        </div>

      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?php echo $total_shipping_completed; ?></h3>

          <p>Completed Shipping</p>
        </div>
        <div class="icon">
          <i class="ionicons ion-checkmark-circled"></i>
        </div>

      </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-orange">
        <div class="inner">
          <h3><?php echo $total_order_pending; ?></h3>

          <p>Pending Shippings</p>
        </div>
        <div class="icon">
          <i class="ionicons ion-load-a"></i>
        </div>

      </div>
    </div>


    <!-- <div class="col-lg-3 col-xs-6">
      small box
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3><?php echo $total_subscriber; ?></h3>

          <p>Subscriber</p>
        </div>
        <div class="icon">
          <i class="ionicons ion-person-add"></i>
        </div>

      </div>
    </div> -->

  </div>

</section>

<?php require_once('../admin/footer.php'); ?>