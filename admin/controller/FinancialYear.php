<?php


$path = str_replace("\\", "/", dirname(__DIR__));
require_once $path . "/model/FinancialYearModel.php";
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
    foreach ($ids as $id) {
      $this->model->deleteById($id);
    }
    $_SESSION["toast_message"] = "Sucessfully Deleted";
    $_SESSION["toast_type"] = "alert-success";
    exit();
  }
}

//{--------------CALLING CONTROLLER METHODS--------------
$fy_controller = new FinancialYear;


$years = $fy_controller->findAll();

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