<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<?php
	$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
	$statement->execute(array($_REQUEST['id']));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) {
		$p_featured_photo = $row['p_featured_photo'];
		unlink('../assets/uploads/'.$p_featured_photo);
	}

	$statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
	$statement->execute(array($_REQUEST['id']));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) {
		$photo = $row['photo'];
		unlink('../assets/uploads/product_photos/'.$photo);
	}

	$statement = $pdo->prepare("DELETE FROM tbl_product WHERE p_id=?");
	$statement->execute(array($_REQUEST['id']));

	$statement = $pdo->prepare("DELETE FROM tbl_product_photo WHERE p_id=?");
	$statement->execute(array($_REQUEST['id']));

	$statement = $pdo->prepare("DELETE FROM tbl_product_size WHERE p_id=?");
	$statement->execute(array($_REQUEST['id']));

	$statement = $pdo->prepare("DELETE FROM tbl_product_color WHERE p_id=?");
	$statement->execute(array($_REQUEST['id']));

	$statement = $pdo->prepare("DELETE FROM tbl_rating WHERE p_id=?");
	$statement->execute(array($_REQUEST['id']));

	$statement = $pdo->prepare("SELECT * FROM tbl_order WHERE product_id=?");
	$statement->execute(array($_REQUEST['id']));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) {
		$statement1 = $pdo->prepare("DELETE FROM tbl_payment WHERE payment_id=?");
		$statement1->execute(array($row['payment_id']));
	}

	$statement = $pdo->prepare("DELETE FROM tbl_order WHERE product_id=?");
	$statement->execute(array($_REQUEST['id']));

	header('location: product.php');
?>