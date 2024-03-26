<?php  

include("../admin/controller/FinancialYear.php");
include("../admin/controller/Album.php");
include("../admin/controller/Gallery.php");


if (!empty($_SERVER['QUERY_STRING'])) {
  $fy_q = explode("=", $_SERVER['QUERY_STRING'])[0];
  $fy_id = explode("=", $_SERVER['QUERY_STRING'])[1];
  if($fy_q=="fyID" and !empty($fy_id)) {
    $yearData=$fy_controller->findOne($fy_id);
    $albumData = $album_controller->findAllActive("year_id",$fy_id);
    $data=["year"=>$yearData,"albums"=>$albumData];
  
    $json_data =json_encode($data);
    
    echo $json_data;
    exit;
  }



}


?>