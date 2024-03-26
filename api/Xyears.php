<?php

include("../admin/controller/FinancialYear.php");

$active_years = $fy_controller->findAllActive();
$data = ["years" => $active_years];

$json_data = json_encode($data);
// Set the content type header to application/json
header('Content-Type: application/json');
$json_data = json_encode(strip_tags($data));
// Output the JSON data
echo $json_data;





?>