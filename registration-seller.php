<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
  $banner_registration = $row['banner_registration'];
}
?>

<?php
if (isset($_POST['form1'])) {
  $valid = 1;

  if (empty($_POST['seller_name'])) {
    $valid = 0;
    $error_message .= "Seller Name can not be empty." . "<br>";
  }

  if (empty($_POST['seller_uname'])) {
    $valid = 0;
    $error_message .= "Username cannot be empty" . "<br>";
  } else {
    if (str_contains($_POST['seller_uname'], ' ')) {
      $valid = 0;
      $error_message .= "Username shouldn't have space in it" . "<br>";
    } else {
      $statement = $pdo->prepare("SELECT * FROM tbl_seller WHERE seller_uname=?");
      $statement->execute(array($_POST['seller_uname']));
      $uname_exist = $statement->rowCount();
      if ($uname_exist) {
        $valid = 0;
        $error_message .= "Username already exists" . "<br>";
      }
    }
  }

  if (empty($_POST['seller_email'])) {
    $valid = 0;
    $error_message .= "Email Address can not be empty" . "<br>";
  } else {
    if (filter_var($_POST['seller_email'], FILTER_VALIDATE_EMAIL) === false) {
      $valid = 0;
      $error_message .= "Email address must be valid. " . "<br>";
    } else {
      $statement = $pdo->prepare("SELECT * FROM tbl_seller WHERE seller_email=?");
      $statement->execute(array($_POST['seller_email']));
      $total = $statement->rowCount();
      if ($total) {
        $valid = 0;
        $error_message .= "Email Address Already Exists." . "<br>";
      }
    }
  }

  if (empty($_POST['seller_phone'])) {
    $valid = 0;
    $error_message .= "Phone Number can not be empty." . "<br>";
  }

  if (empty($_POST['seller_address'])) {
    $valid = 0;
    $error_message .= "Address can not be empty." . "<br>";
  }

  if (empty($_POST['seller_country'])) {
    $valid = 0;
    $error_message .= "You must have to select a country." . "<br>";
  }

  if (empty($_POST['seller_city'])) {
    $valid = 0;
    $error_message .= "City can not be empty." . "<br>";
  }

  if (empty($_POST['seller_state'])) {
    $valid = 0;
    $error_message .= "State can not be empty." . "<br>";
  }

  if (empty($_POST['seller_zip'])) {
    $valid = 0;
    $error_message .= "Zip Code can not be empty." . "<br>";
  }

  if (empty($_POST['seller_password']) || empty($_POST['seller_re_password'])) {
    $valid = 0;
    $error_message .= "Password can not be empty." . "<br>";
  }

  if (!empty($_POST['seller_password']) && !empty($_POST['seller_re_password'])) {
    if ($_POST['seller_password'] != $_POST['seller_re_password']) {
      $valid = 0;
      $error_message .= "Passwords do not match." . "<br>";
    }
  }

  if ($valid == 1) {

    $token = md5(time());
    $seller_datetime = date('Y-m-d h:i:s');
    $seller_timestamp = time();

    $statement = $pdo->prepare("INSERT INTO tbl_seller (
                                        seller_name,
                                        seller_uname,
                                        seller_email,
                                        seller_phone,
                                        seller_country,
                                        seller_address,
                                        seller_city,
                                        seller_state,
                                        seller_zip,
                                        seller_b_name,
                                        seller_b_uname,
                                        seller_b_phone,
                                        seller_b_country,
                                        seller_b_address,
                                        seller_b_city,
                                        seller_b_state,
                                        seller_b_zip,
                                        seller_s_name,
                                        seller_s_sname,
                                        seller_s_phone,
                                        seller_s_country,
                                        seller_s_address,
                                        seller_s_city,
                                        seller_s_state,
                                        seller_s_zip,
                                        seller_password,
                                        seller_token,
                                        seller_datetime,
                                        seller_timestamp,
                                        seller_status,
                                        post_num
                                    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $statement->execute(array(
      strip_tags($_POST['seller_name']),
      strip_tags($_POST['seller_uname']),
      strip_tags($_POST['seller_email']),
      strip_tags($_POST['seller_phone']),
      strip_tags($_POST['seller_country']),
      strip_tags($_POST['seller_address']),
      strip_tags($_POST['seller_city']),
      strip_tags($_POST['seller_state']),
      strip_tags($_POST['seller_zip']),
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      md5($_POST['seller_password']),
      $token,
      $seller_datetime,
      $seller_timestamp,
      0, 0
    ));

    ini_set("SMTP", "smtp.gmail.com");
    ini_set("sendmail_from", "miracleaureliarec@gmail.com");
    ini_set("smtp_port", 465);
    ini_set("username", "miracleaureliarec@gmail.com");
    ini_set("password", "ThisIsStrongPass255");

    $to = $_POST['seller_email'];

    $subject = "Registration Email Confirmation for GoToStore.";
    $verify_link = 'http://127.0.0.1/gotostore-php/verify.php?email=' . $to . '&token=' . $token;
    $message = '
' . "Thank you for your registration! Your account has been created. To active your account click on the link below: " . '<br><br>

<a href="' . $verify_link . '">' . $verify_link . '</a>';

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: miracleaureliarec@gmail.com";

    if (mail($to, $subject, $message, $headers)) {
      echo "<script type='text/javascript'>alert('We have sent a verification email. Please verify your account soon');</script>";
    }

    unset($_POST['seller_name']);
    unset($_POST['seller_uname']);
    unset($_POST['seller_email']);
    unset($_POST['seller_phone']);
    unset($_POST['seller_address']);
    unset($_POST['seller_city']);
    unset($_POST['seller_state']);
    unset($_POST['seller_zip']);

    $success_message = "Your registration is completed. Please check your email address to follow the process to confirm your registration.";
  }
}
?>

<div class="page-banner" style="background-color:#444;background-image: url(assets/uploads/<?php echo $banner_registration; ?>);">
  <div class="inner">
    <h3><?php echo "Seller Registration"; ?></h3>
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
              <div class="col-md-2"></div>
              <div class="col-md-8">

                <?php
                if ($error_message != '') {
                  echo "<div class='error' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $error_message . "</div>";
                }
                if ($success_message != '') {
                  echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $success_message . "</div>";
                }
                ?>

                <div class="col-md-6 form-group">
                  <label for=""><?php echo "Full Name"; ?> *</label>
                  <input type="text" class="form-control" name="seller_name" value="<?php if (isset($_POST['seller_name'])) {
                                                                                      echo $_POST['seller_name'];
                                                                                    } ?>">
                </div>
                <div class="col-md-6 form-group">
                  <label for=""><?php echo "Username"; ?> *</label>
                  <input type="text" class="form-control" name="seller_uname" value="<?php if (isset($_POST['seller_uname'])) {
                                                                                        echo $_POST['seller_uname'];
                                                                                      } ?>">
                </div>
                <div class="col-md-6 form-group">
                  <label for=""><?php echo "Email Address"; ?> *</label>
                  <input type="email" class="form-control" name="seller_email" value="<?php if (isset($_POST['seller_email'])) {
                                                                                        echo $_POST['seller_email'];
                                                                                      } ?>">
                </div>
                <div class="col-md-6 form-group">
                  <label for=""><?php echo "Phone Number"; ?> *</label>
                  <input type="text" class="form-control" name="seller_phone" value="<?php if (isset($_POST['seller_phone'])) {
                                                                                        echo $_POST['seller_phone'];
                                                                                      } ?>">
                </div>
                <div class="col-md-12 form-group">
                  <label for=""><?php echo "Address"; ?> *</label>
                  <textarea name="seller_address" class="form-control" cols="30" rows="10" style="height:70px;"><?php if (isset($_POST['seller_address'])) {
                                                                                                                  echo $_POST['seller_address'];
                                                                                                                } ?></textarea>
                </div>
                <div class="col-md-6 form-group">
                  <label for=""><?php echo "Country"; ?> *</label>
                  <select name="seller_country" class="form-control select2">
                    <option value="">Select country</option>
                    <?php
                    $statement = $pdo->prepare("SELECT * FROM tbl_country ORDER BY country_name ASC");
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                    ?>
                      <option value="<?php echo $row['country_id']; ?>"><?php echo $row['country_name']; ?></option>
                    <?php
                    }
                    ?>
                  </select>
                </div>

                <div class="col-md-6 form-group">
                  <label for=""><?php echo "City"; ?> *</label>
                  <input type="text" class="form-control" name="seller_city" value="<?php if (isset($_POST['seller_city'])) {
                                                                                      echo $_POST['seller_city'];
                                                                                    } ?>">
                </div>
                <div class="col-md-6 form-group">
                  <label for=""><?php echo "State"; ?> *</label>
                  <input type="text" class="form-control" name="seller_state" value="<?php if (isset($_POST['seller_state'])) {
                                                                                        echo $_POST['seller_state'];
                                                                                      } ?>">
                </div>
                <div class="col-md-6 form-group">
                  <label for=""><?php echo "Zip Code"; ?> *</label>
                  <input type="text" class="form-control" name="seller_zip" value="<?php if (isset($_POST['seller_zip'])) {
                                                                                      echo $_POST['seller_zip'];
                                                                                    } ?>">
                </div>
                <div class="col-md-6 form-group">
                  <label for=""><?php echo "Password"; ?> *</label>
                  <input type="password" class="form-control" name="seller_password">
                </div>
                <div class="col-md-6 form-group">
                  <label for=""><?php echo "Retype Password"; ?> *</label>
                  <input type="password" class="form-control" name="seller_re_password">
                </div>
                <div class="col-md-6 form-group">
                  <label for=""></label>
                  <input type="submit" class="btn btn-danger" value="<?php echo "Register"; ?>" name="form1">
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

<script>
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
</script>