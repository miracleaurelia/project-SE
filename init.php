<?php
require("vendor/autoload.php");

use Google\Cloud\Vision\VisionClient;

$vision = new VisionClient(['keyFile' => json_decode(file_get_contents('key.json'), true)]);

$familyPhotoResource = fopen('images/product_10.png', 'r');
$image;
$result;
try {
  $image = $vision->image($familyPhotoResource, ['FACE_DETECTION', 'WEB_DETECTION']);
  $result = $vision->annotate($image);
} catch (Exception $e) {
  echo $e->getMessage();
}


var_dump($result);
