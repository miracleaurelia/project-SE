<?php require_once('header.php'); ?>

<?php
if (!isset($_REQUEST['id']) || !isset($_REQUEST['task'])) {
	header('location: logout.php');
	exit;
} else {
	$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_id = ?");
	$statement->execute(array(strip_tags(trim($_REQUEST['id']))));
	$total = $statement->rowCount();
	if ($total == 0) {
		header('location: logout.php');
		exit;
	}
}
?>

<?php
$statement = $pdo->prepare("UPDATE tbl_payment SET shipping_status=? WHERE payment_id=?");
$statement->execute(array($_REQUEST['task'], $_REQUEST['id']));

header('location: order.php');
?>