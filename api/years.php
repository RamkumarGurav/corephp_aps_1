<?php

include("../admin/controller/FinancialYear.php");
include("../admin/controller/Album.php");
include("../admin/controller/Gallery.php");


$albumData = $fy_controller->findAllActive();
$data = ["years" => $albumData];

$json_data = json_encode($data);

echo $json_data;
exit;





?>