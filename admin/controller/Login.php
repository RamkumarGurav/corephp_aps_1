<?php


$root_path = str_replace("\\", "/", dirname(dirname(__DIR__)));
require_once $root_path . "/admin/model/LoginModel.php";
$base_url = "http://localhost/xampp/MARS/appolopublicschool.com/";


class Login
{
  public function doLogin($email, $password)
  {
    // $password = md5($password);



    $model = new LoginModel();
    $user = $model->findUser($email, $password);


    if ($user === false) {

      session_start();
      // $error_msg = "Invalid email or password. Please try again.";
      $_SESSION["toast_message"] = "Invalid email or password. Please try again.";
      $_SESSION["toast_type"] = "alert-danger";

      global $base_url;
      // Redirect the user to the welcome page after successfully adding album photos
      header("Location: {$base_url}admin/index.php");
      exit();
    } else {

      session_start();
      $_SESSION['user'] = ["name" => $user["name"], "email" => $user["email"]];
      $_SESSION["toast_message"] = "Successfully Logged";
      $_SESSION["toast_type"] = "alert-success";


      global $base_url;
      // Redirect the user to the welcome page after successfully adding album photos
      header("Location: {$base_url}admin/dashboard/dashboard.php");
      exit();
    }
  }


  public function logout()
  {

    // Destroy the session and redirect to the login page
    session_destroy();
    session_start();
    $_SESSION["toast_message"] = "Successfully Logged Out";

    global $base_url;
    $_SESSION["toast_type"] = "alert-success";
    header("Location: {$base_url}admin/index.php");
    exit();
  }

  public function test()
  {

    echo "TEST TEST <br>";
    exit;
  }
}

//{--------------CALLING CONTROLLER METHODS--------------
$login_controller = new Login;




// Login logic
if (isset($_POST["signin"])) {
  $email = $_POST["email"];
  $password = $_POST["password"];
  $login_controller->doLogin($email, $password);
  exit;
}

// Logout logic
if (isset($_POST['logout'])) {
  $login_controller->logout();

  exit(); // Ensure no further code is executed
}

//--------------------------------------------------}