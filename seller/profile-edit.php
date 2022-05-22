<?php require_once('header.php'); ?>

<?php
if (isset($_POST['form1'])) {

	if ($_SESSION['seller']['seller_name'] == !'') {
		$valid = 1;
		if (empty($_POST['seller_name'])) {
			$valid = 0;
			$error_message .= "Name can not be empty<br>";
		}

		if (empty($_POST['seller_email'])) {
			$valid = 0;
			$error_message .= 'Email address can not be empty<br>';
		} else {
			if (filter_var($_POST['seller_email'], FILTER_VALIDATE_EMAIL) === false) {
				$valid = 0;
				$error_message .= 'Email address must be valid<br>';
			} else {
				$statement = $pdo->prepare("SELECT * FROM tbl_seller WHERE id=?");
				$statement->execute(array($_SESSION['seller']['id']));
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach ($result as $row) {
					$current_email = $row['seller_email'];
				}

				$statement = $pdo->prepare("SELECT * FROM tbl_seller WHERE seller_email=? and seller_email!=?");
				$statement->execute(array($_POST['seller_email'], $current_email));
				$total = $statement->rowCount();
				if ($total) {
					$valid = 0;
					$error_message .= 'Email address already exists<br>';
				}
			}
		}

		if ($valid == 1) {

			$_SESSION['seller']['seller_name'] = $_POST['seller_name'];
			$_SESSION['seller']['seller_email'] = $_POST['seller_email'];

			$statement = $pdo->prepare("UPDATE tbl_seller SET seller_name=?, seller_email=?, seller_phone=? WHERE id=?");
			$statement->execute(array($_POST['seller_name'], $_POST['seller_email'], $_POST['phone'], $_SESSION['seller']['id']));

			$success_message = 'seller Information is updated successfully.';
		}
	} else {
		$_SESSION['seller']['seller_phone'] = $_POST['phone'];

		$statement = $pdo->prepare("UPDATE tbl_seller SET seller_phone=? WHERE id=?");
		$statement->execute(array($_POST['phone'], $_SESSION['seller']['id']));

		$success_message = 'seller Information is updated successfully.';
	}
}

if (isset($_POST['form2'])) {

	$valid = 1;

	$path = $_FILES['photo']['name'];
	$path_tmp = $_FILES['photo']['tmp_name'];

	if ($path != '') {
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		$file_name = basename($path, '.' . $ext);
		if ($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif') {
			$valid = 0;
			$error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
		}
	}

	if ($valid == 1) {
		if ($_SESSION['seller']['photo'] != '') {
			unlink('../assets/uploads/' . $_SESSION['seller']['photo']);
		}

		$final_name = 'seller-' . $_SESSION['seller']['id'] . '.' . $ext;
		move_uploaded_file($path_tmp, '../assets/uploads/' . $final_name);
		$_SESSION['seller']['photo'] = $final_name;

		$statement = $pdo->prepare("UPDATE tbl_seller SET seller_photo=? WHERE id=?");
		$statement->execute(array($final_name, $_SESSION['seller']['id']));

		$success_message = 'seller Photo is updated successfully.';
	}
}

if (isset($_POST['form3'])) {
	$valid = 1;

	if (empty($_POST['password']) || empty($_POST['re_password'])) {
		$valid = 0;
		$error_message .= "Password can not be empty<br>";
	}

	if (!empty($_POST['password']) && !empty($_POST['re_password'])) {
		if ($_POST['password'] != $_POST['re_password']) {
			$valid = 0;
			$error_message .= "Passwords do not match<br>";
		}
	}

	if ($valid == 1) {

		$_SESSION['seller']['password'] = md5($_POST['password']);

		$statement = $pdo->prepare("UPDATE tbl_seller SET seller_password=? WHERE id=?");
		$statement->execute(array(md5($_POST['password']), $_SESSION['seller']['id']));

		$success_message = 'seller Password is updated successfully.';
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Edit Profile</h1>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_seller WHERE id=?");
$statement->execute(array($_SESSION['seller']['id']));
$statement->rowCount();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$seller_name = $row['seller_name'];
	$email     = $row['seller_email'];
	$phone     = $row['seller_phone'];
	$photo     = $row['seller_photo'];
	$status    = $row['seller_status'];
}
?>


<section class="content">

	<div class="row">
		<div class="col-md-12">

			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_1" data-toggle="tab">Update Information</a></li>
					<li><a href="#tab_2" data-toggle="tab">Update Photo</a></li>
					<li><a href="#tab_3" data-toggle="tab">Update Password</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">

						<form class="form-horizontal" action="" method="post">
							<div class="box box-info">
								<div class="box-body">
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Name <span>*</span></label>
										<?php
										if ($_SESSION['seller']['seller_name'] != '') {
										?>
											<div class="col-sm-4">
												<input type="text" class="form-control" name="seller_name" value="<?php echo $seller_name; ?>">
											</div>
										<?php
										}
										?>

									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Existing Photo</label>
										<div class="col-sm-6" style="padding-top:6px;">
											<img src="../assets/uploads/<?php echo $photo; ?>" class="existing-photo" width="140">
										</div>
									</div>

									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Email Address <span>*</span></label>
										<?php
										if ($_SESSION['seller']['seller_email'] != '') {
										?>
											<div class="col-sm-4">
												<input type="email" class="form-control" name="seller_email" value="<?php echo $email; ?>">
											</div>
										<?php } ?>

									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Phone </label>
										<div class="col-sm-4">
											<input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-6">
											<button type="submit" class="btn btn-success pull-left" name="form1">Update Information</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="tab-pane" id="tab_2">
						<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
							<div class="box box-info">
								<div class="box-body">
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">New Photo</label>
										<div class="col-sm-6" style="padding-top:6px;">
											<input type="file" name="photo">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-6">
											<button type="submit" class="btn btn-success pull-left" name="form2">Update Photo</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="tab-pane" id="tab_3">
						<form class="form-horizontal" action="" method="post">
							<div class="box box-info">
								<div class="box-body">
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Password </label>
										<div class="col-sm-4">
											<input type="password" class="form-control" name="password">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Retype Password </label>
										<div class="col-sm-4">
											<input type="password" class="form-control" name="re_password">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-6">
											<button type="submit" class="btn btn-success pull-left" name="form3">Update Password</button>
										</div>
									</div>
								</div>
							</div>
						</form>

					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<?php require_once('../admin/footer.php'); ?>