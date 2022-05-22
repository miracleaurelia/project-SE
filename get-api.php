<?php
require_once('header.php');
require("vendor/autoload.php");

use Google\Cloud\Vision\VisionClient;

$vision = new VisionClient(['keyFile' => json_decode(file_get_contents('key.json'), true)]);
if ($_FILES['Image-Classification']['name'] == '') {
  header("location: image-upload.php");
  die();
}
$familyPhotoResource = fopen($_FILES['Image-Classification']['tmp_name'], 'r');
$image;
$result;
try {

  $image = $vision->image($familyPhotoResource, ['FACE_DETECTION', 'WEB_DETECTION', 'LABEL_DETECTION']);
  $result = $vision->annotate($image);
} catch (Exception $e) {
  fclose($familyPhotoResource);
  header("location: image-upload.php");
  die();
}
if ($result) {
  $extension = explode('.', $_FILES['Image-Classification']['name']);
  $fileExtension = strtolower(end($extension));
  $filenames = uniqid(reset($extension), true) . '.' . $fileExtension;
  $imagetoken = random_int(10000000, 9999999999);
  move_uploaded_file(
    $_FILES['Image-Classification']['tmp_name'],
    __DIR__ . '/images/API/' . $filenames
  );
  // fclose($familyPhotoResource);
  // header("location: image-upload.php");
  // exit();
} else {
  header("location: image-upload.php");
  die();
}
$faces = $result->faces();
$logos = $result->logos();
$labels = $result->labels();
$landmarks = $result->landmarks();
$text = $result->text();
$fullText = $result->fullText();
$cropHints = $result->cropHints();
$web = $result->web();
$safeSearch = $result->safeSearch();
$imageProperties = $result->imageProperties();

?>
<section>
  <div class="container-fluid">
    <div class="row justify-content-center py-5 my-5">
      <div class="col-md-6">
        <div class="panel-heading">
          <h2 class="text-center">Image Classification</h2>
          <hr>
          <div class="row">
            <div class="col-4 text-center pb-2 full mt-3">Images</div>
            <p class="text-center col-8 pb-2 full mt-3">Image Classification With Google API</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4" style="text-align: center">
            <img alt="Analyzed Image" class="img-tumbnail w-75" src="<?php echo './images/API/' . $filenames; ?>">
            <h6 class="mt-5">Uploaded Image</h6>
          </div>
          <div class="col-md-8 border" style="padding : 10px">
            <ul class="nav bg-transparent nav-pills nav-fill mb-3" id="pills-menu" role="tablist">
              <li class="nav-item">
                <a href="#pills-labels" role="tab" class="nav-link rounded mx-2 active" id="pills-labels-tab" data-toggle="pill" aria-controls="pills-labels" aria-selected="true">labels</a>
              </li>
              <li class="nav-item">
                <a href="#pills-webs" role="tab" class="nav-link rounded mx-2" id="pills-webs-tab" data-toggle="pill" aria-controls="pills-webs" aria-selected="true">webs</a>
              </li>
            </ul>
            <hr>
            <div class="tab-content" id="pills-tabContent">

              <div class="tab-pane fade show active" id="pills-labels" role="tabpanel" aria-lavelledby="pills-labels-tab">
                <div class="row">
                  <div class="col-12">
                    <?php include_once("labels.php"); ?>
                  </div>
                </div>
              </div>

              <div class="tab-pane fade show" id="pills-webs" role="tabpanel" aria-lavelledby="pills-webs-tab">
                <div class="row">
                  <div class="col-12">
                    <?php include_once("web.php"); ?>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once('footer.php'); ?>