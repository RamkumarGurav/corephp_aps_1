<?php  

include("../admin/controller/FinancialYear.php");
include("../admin/controller/Album.php");
include("../admin/controller/Gallery.php");


if (!empty($_SERVER['QUERY_STRING'])) {

  $album_q = explode("=", $_SERVER['QUERY_STRING'])[0];
  $album_id = explode("=", $_SERVER['QUERY_STRING'])[1];
  if($album_q=="albumID" and !empty($album_id)) {
  $albumData=$album_controller->findOne($album_id);
  $yearData=$fy_controller->findOne($albumData['year_id']);
  $albumImagesData = $gallery_controller->findAllActive("album_id",$album_id);
  $data=["year"=>$yearData,"album"=>$albumData,"albumImages"=>$albumImagesData];

  $json_data =json_encode($data);
  
echo $json_data;
exit;
  }
}


?>