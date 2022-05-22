<?php
require_once('header.php');
if (!isset($_SESSION['customer'])) {
  header('location: logout.php');
  exit;
} else {
  $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
  $statement->execute();
  $result = $statement->fetchAll(PDO::FETCH_ASSOC);
  foreach ($result as $row) {
    $banner_product_category = $row['banner_product_category'];
  }
}

?>

<section>
  <div class="container">
    <div class="row justify-content-center py-5 my-5">
      <div class="col-6">
        <div class="panel-heading text-center">
          <h2>Image Search</h2>
        </div>
        <form class="mt-5" action="get-api.php" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <input class="" name="Image-Classification" type="file" class="form-control-file">
            <div class="d-flex justify-content-center">
              <button id="Image" type="submit" class="w-100 flex-fill btn btn-danger mt-4">Analyze</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script type="text/javascript">
</script>

<?php require_once('footer.php') ?>