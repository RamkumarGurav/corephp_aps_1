<?php


$root_path = str_replace("\\", "/", dirname(dirname(__DIR__)));
require_once $root_path . "/admin/model/FinancialYearModel.php";
$base_url = "http://localhost/xampp/MARS/appolopublicschool.com";
$current_path = $_SERVER['REQUEST_URI'];

class FinancialYear
{

  public $model;

  public function __construct()
  {
    $this->model = new FinancialYearModel();
  }


  public function findAll()
  {
    return $this->model->findAll();
  }
  public function findAllActive()
  {
    return $this->model->findAllActive();
  }

  public function findOne($id)
  {
    return $this->model->findOneByColumnName("id", $id);
  }
  public function findOneByFiscalYear($fiscal_year)
  {
    return $this->model->findOneByColumnName("fiscal_year", $fiscal_year);
  }

  public function createFY($post)
  {



    $data = [
      "start_year" => $post["start_year"], "end_year" => $post["end_year"],
      "fiscal_year" => $post["fiscal_year"], "status" => $post["status"]
    ];


    if ($this->model->findOneByColumnName("fiscal_year", $data["fiscal_year"]) != false) {
      $_SESSION["toast_message"] = "This financial year already exists";
      $_SESSION["toast_type"] = "alert-danger";
      header("Location: http://localhost/xampp/MARS/appolopublicschool.com/admin/financial-year/edit.php");
      exit;
    } else {
      $id = $this->model->createOne($data);

      if ($id === false) {

        $_SESSION["toast_message"] = "Unable to Create Financial Year.";
        $_SESSION["toast_type"] = "alert-danger";
        header("Location: http://localhost/xampp/MARS/appolopublicschool.com/admin/financial-year/edit.php");
        exit;
      } else {

        $_SESSION["toast_message"] = "Successfully Created Financial Year";
        $_SESSION["toast_type"] = "alert-success";
        header("Location: http://localhost/xampp/MARS/appolopublicschool.com/admin/financial-year/listing.php");
        exit;
      }
    }
  }


  public function updateFY($id, $data)
  {

    $isUpdated = $this->model->updateOneByColumnName("id", $id, $data);

    if ($isUpdated === false) {

      $_SESSION["toast_message"] = "Unable to Update Financial Year.";
      $_SESSION["toast_type"] = "alert-danger";
      header("Location: http://localhost/xampp/MARS/appolopublicschool.com/admin/financial-year/edit.php");
      exit;
    } else {

      $_SESSION["toast_message"] = "Successfully Updated Financial Year";
      $_SESSION["toast_type"] = "alert-success";
      header("Location: http://localhost/xampp/MARS/appolopublicschool.com/admin/financial-year/listing.php");
      exit;
    }
  }


  public  function activate()
  {

    $ids = $_POST["ids"];
    // var_dump($ids);
    foreach ($ids as $id) {
      $this->model->updateOneByColumnName('id', $id, ["status" => "1"]);
    }
    $_SESSION["toast_message"] = "Sucessfully Activated";
    $_SESSION["toast_type"] = "alert-success";
    exit();
  }

  public function block()
  {
    $ids = $_POST["ids"];
    foreach ($ids as $id) {
      $this->model->updateOneByColumnName('id', $id, ["status" => "0"]);
    }
    $_SESSION["toast_message"] = "Sucessfully Blocked";
    $_SESSION["toast_type"] = "alert-success";
    exit();
  }


  public function delete()
  {
    $ids = $_POST["ids"];


    if (!empty($ids)) {
      foreach ($ids as $id) {
        // Retrieve album data to get the cover image filename
        $yearData = $this->model->findOneByColumnName("id", $id);
        if (
          !empty($yearData)
        ) {
          // Delete the record from the album table
          if ($this->model->deleteById($id)) {
            // Check if the cover image exists and delete it from the uploads folder
            global $root_path;
            $root_uploads_folder = $root_path . "/uploads";
            $fy_folder_path = $root_uploads_folder . "/album/{$yearData['fiscal_year']}";


            if (file_exists($fy_folder_path)) {

              if ($this->deleteFolderContents($fy_folder_path)) {
                rmdir($fy_folder_path);
              }; // Delete the image file
            }
          }
        }
      }
    }
  }


  function deleteFolderContents($folderPath)
  {
    // Check if the folder exists
    if (!is_dir($folderPath)) {
      return false;
    }

    // Get the list of files and directories inside the folder
    $items = scandir($folderPath);

    // Iterate through each item
    foreach ($items as $item) {
      // Skip "." and ".." special directories
      if ($item == '.' || $item == '..') {
        continue;
      }

      // Build the full path to the item
      $itemPath = $folderPath . '/' . $item;

      // Check if the item is a directory
      if (is_dir($itemPath)) {
        // If it's a directory, recursively delete its contents
        $this->deleteFolderContents($itemPath);
        // After deleting the contents, delete the directory itself
        rmdir($itemPath);
      } else {
        // If it's a file, simply delete it
        unlink($itemPath);
      }
    }

    return true;
  }
}

//{--------------CALLING CONTROLLER METHODS--------------
$fy_controller = new FinancialYear;


$years = $fy_controller->findAll();
$years_count = count($years);

$yearId = null;
$yearData = null;


if (!empty($_SERVER['QUERY_STRING'])) {
  $yearId = explode("=", $_SERVER['QUERY_STRING'])[1];
  $yearData = $fy_controller->findOne($yearId);


  if (isset($_POST["submit_year_btn"]) && (!empty($_POST["fiscal_year"]))) {
    $data = [
      "start_year" => $_POST["start_year"], "end_year" => $_POST["end_year"],
      "fiscal_year" => $_POST["fiscal_year"], "status" => $_POST["status"]
    ];

    $fy_controller->updateFY($yearId, $data);

    exit;
  }
} elseif (isset($_POST["submit_year_btn"]) && (!empty($_POST["fiscal_year"]))) {

  $fy_controller->createFY($_POST);
  exit;
}



if (isset($_GET['fy_action'])) {
  if ($_GET['fy_action'] == 'activate') {
    $fy_controller->activate();
  } else if ($_GET['fy_action'] == 'block') {
    $fy_controller->block();
  } else if ($_GET['fy_action'] == 'delete') {
    $fy_controller->delete();
  }
}
//--------------------------------------------------}