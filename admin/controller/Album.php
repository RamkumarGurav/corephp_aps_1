<?php


$root_path = str_replace("\\", "/", dirname(dirname(__DIR__)));
require_once $root_path . "/admin/model/AlbumModel.php";

$base_url = "http://localhost/xampp/MARS/appolopublicschool.com/";

$current_path = $_SERVER['REQUEST_URI'];
class Album
{

  public $model;

  public function __construct()
  {
    $this->model = new AlbumModel();
  }


  public function findAll($columnName = null, $columnValue = null)
  {
    if ($columnName != null) {
      return $this->model->findAllByColumName($columnName, $columnValue);
    } else {
      return $this->model->findAll();
    }
  }
  public function findAllActive($columnName = null, $columnValue = null)
  {
    if ($columnName != null) {
      return $this->model->findAllActiveByColumName($columnName, $columnValue);
    } else {
      return $this->model->findAllActive();
    }
  }

  public function findOne($id)
  {
    return $this->model->findOneByColumnName("id", $id);
  }



  public function createAlbumAndAddCoverImage($data, $year_id)
  {
    $file_name = $_FILES['cover_image']['name'];
    $file_tmp = $_FILES['cover_image']['tmp_name'];


    // Check if the file has been uploaded
    if (!empty($file_name)) {
      // Get the file name, type, and size
      $file_name = $_FILES['cover_image']['name'];
      $file_type = $_FILES['cover_image']['type'];
      $file_size = $_FILES['cover_image']['size'];

      // Define allowed file types and maximum file size
      $allowed_types = array('image/png', 'image/jpeg', 'image/jpg');
      $max_file_size = 2 * 1024 * 1024; // 2 MB

      //ti Check image dimensions
      list($width, $height) = getimagesize($file_tmp);

      // Check if the file type is allowed
      if (!in_array($file_type, $allowed_types)) {
        // File type not allowed
        // Handle the error or display a message
        // echo "Error: Only PNG, JPEG, and JPG file types are allowed.";

        $_SESSION["album_data"] = $data;
        $_SESSION["toast_message"] = "Only PNG, JPEG, and JPG Image types are allowed.";
        $_SESSION["toast_type"] = "alert-danger";
        global $base_url;
        header("Location: {$base_url}admin/album/edit.php");
        exit;
      } elseif ($file_size > $max_file_size) {
        // File size exceeds the limit
        // Handle the error or display a message
        // echo "Error: File size should not exceed 2MB.";
        $_SESSION["album_data"] = $data;
        $_SESSION["toast_message"] = "Image size should not exceed 2MB.";
        $_SESSION["toast_type"] = "alert-danger";
        global $base_url;
        header("Location: {$base_url}admin/album/edit.php");
        exit;
      } elseif ($width < 650 || $height < 400) {
        $_SESSION["album_data"] = $data;
        $_SESSION["toast_message"] = "Minimum image dimensions required is 650 X 400 pixels";
        $_SESSION["toast_type"] = "alert-danger";
        global $base_url;
        header("Location: {$base_url}admin/album/edit.php");
        exit;
      } else {




        $album_id = $this->model->createOne($data);




        // Check if the album was successfully inserted
        if ($album_id != false) {
          // Retrieve the uploaded cover image details
          $file_name = $_FILES['cover_image']['name'];
          $file_tmp = $_FILES['cover_image']['tmp_name'];


          if (!empty($file_name)) {
            // Retrieve the fiscal year from the year table based on the year_id
            global $fy_controller;
            $yearRecord = $fy_controller->findOne($year_id);
            // Initialize fiscal year variable
            $fy_year = null;

            if (!$yearRecord == false) {
              // Assign the fiscal year to the variable
              $fy_year = $yearRecord["fiscal_year"];
            } else {
              $_SESSION["toast_message"] = "Unable find the Year";
              $_SESSION["toast_type"] = "alert-danger";
              global $base_url;
              header("Location: {$base_url}admin/album/edit.php");
              exit;
            }






            global $root_path;
            // Move the uploaded cover image to the appropriate folder
            $movedImageName =  $this->moveImageToFolder($album_id, $fy_year, $file_name, $file_tmp, $root_path . "/uploads", "cover_images");


            if ($movedImageName == false) {
              $_SESSION["toast_message"] = "Album created successfully but failed to add the cover image";
              $_SESSION["toast_type"] = "alert-danger";
              global $base_url;
              header("Location: {$base_url}admin/album/edit.php");
              exit;
            }
            // Prepare data for updating the album table with the cover image name
            $data = ["cover_image" => $movedImageName];

            // Update the album table with the cover image name
            $isTableUpdated = $this->model->updateOneByColumnName("id", $album_id, $data);

            // Check if the table update was successful
            if (!$isTableUpdated) {
              // Display error message if the table update failed
              $_SESSION["toast_message"] = "Album created successfully but failed to add the cover image";
              $_SESSION["toast_type"] = "alert-danger";
              global $base_url;
              header("Location: {$base_url}admin/album/edit.php");
              exit;
            }
          }
          $_SESSION["toast_message"] = "Successfully create the Album";
          $_SESSION["toast_type"] = "alert-success";

          // Redirect the user to the welcome page after successful album creation
          global $base_url;
          header("Location: {$base_url}admin/album/listing.php");
          exit();
        } else {
          $_SESSION["toast_message"] = "Failed to create the Album";
          $_SESSION["toast_type"] = "alert-danger";
          global $base_url;
          header("Location: {$base_url}admin/album/edit.php");
          exit;
        }
      }
    }
  }

  public function updateAlbumAndAddCoverImage($id, $data, $year_id)
  {



    $file_name = $_FILES['cover_image']['name'];
    $file_tmp = $_FILES['cover_image']['tmp_name'];


    // Check if the file has been uploaded
    if (!empty($file_name)) {
      // Get the file name, type, and size
      $file_name = $_FILES['cover_image']['name'];
      $file_type = $_FILES['cover_image']['type'];
      $file_size = $_FILES['cover_image']['size'];

      // Define allowed file types and maximum file size
      $allowed_types = array('image/png', 'image/jpeg', 'image/jpg');
      $max_file_size = 2 * 1024 * 1024; // 2 MB

      //to Check image dimensions
      list($width, $height) = getimagesize($file_tmp);

      // Check if the file type is allowed
      if (!in_array($file_type, $allowed_types)) {
        // File type not allowed
        // Handle the error or display a message
        // echo "Error: Only PNG, JPEG, and JPG file types are allowed.";

        $_SESSION["album_data"] = $data;
        $_SESSION["toast_message"] = "Only PNG, JPEG, and JPG Image types are allowed.";
        $_SESSION["toast_type"] = "alert-danger";
        global $base_url;
        header("Location: {$base_url}admin/album/edit.php?albumID=$id");
        exit;
      } elseif ($file_size > $max_file_size) {
        // File size exceeds the limit
        // Handle the error or display a message
        // echo "Error: File size should not exceed 2MB.";
        $_SESSION["album_data"] = $data;
        $_SESSION["toast_message"] = "Image size should not exceed 2MB.";
        $_SESSION["toast_type"] = "alert-danger";
        global $base_url;
        header("Location: {$base_url}admin/album/edit.php?albumID=$id");
        exit;
      } elseif ($width < 650 || $height < 400) {
        $_SESSION["album_data"] = $data;
        $_SESSION["toast_message"] = "Minimum image dimensions required is 650 X 400 pixels";
        $_SESSION["toast_type"] = "alert-danger";
        header("Location: http://localhost/xampp/MARS/appolopublicschool.com/admin/album/edit.php");
        exit;
      } else {


        $isUpdated = $this->model->updateOneByColumnName("id", $id, $data);


        // Check if the album was successfully inserted
        if (isset($isUpdated)) {
          // Retrieve the uploaded cover image details
          $file_name = $_FILES['cover_image']['name'];
          $file_tmp = $_FILES['cover_image']['tmp_name'];
          if (!empty($file_name)) {


            // Retrieve the fiscal year from the year table based on the year_id
            global $fy_controller;
            $yearRecord = $fy_controller->findOne($year_id);
            // Initialize fiscal year variable
            $fy_year = null;

            if ($yearRecord != false) {
              // Assign the fiscal year to the variable
              $fy_year = $yearRecord["fiscal_year"];
            } else {
              $_SESSION["toast_message"] = "Unable find the Year";
              $_SESSION["toast_type"] = "alert-danger";
              header("Location: http://localhost/xampp/MARS/appolopublicschool.com/admin/album/edit.php?albumID={$id}");
              exit;
            }



            global $root_path;
            // Move the uploaded cover image to the appropriate folder
            $movedImageName =  $this->moveImageToFolder($id, $fy_year, $file_name, $file_tmp, $root_path . "/uploads", "cover_images");


            if ($movedImageName == false) {
              $_SESSION["toast_message"] = "Album updated successfully but failed to add the cover image";
              $_SESSION["toast_type"] = "alert-danger";
              header("Location: http://localhost/xampp/MARS/appolopublicschool.com/admin/album/edit.php?albumID={$id}");
              exit;
            }
            // Prepare data for updating the album table with the cover image name
            $data = ["cover_image" => $movedImageName];

            // Update the album table with the cover image name
            $isTableUpdated = $this->model->updateOneByColumnName("id", $id, $data);

            // Check if the table update was successful
            if (!$isTableUpdated) {
              // Display error message if the table update failed
              $_SESSION["toast_message"] = "Album updated successfully but failed to add the cover image";
              $_SESSION["toast_type"] = "alert-danger";
              header("Location: http://localhost/xampp/MARS/appolopublicschool.com/admin/album/edit.php?albumID={$id}");
              exit;
            }
          }

          $_SESSION["toast_message"] = "Successfully updated the Album";
          $_SESSION["toast_type"] = "alert-success";

          // Redirect the user to the welcome page after successful album creation
          header("Location: http://localhost/xampp/MARS/appolopublicschool.com/admin/album/show.php?albumID={$id}");
          exit();
        } else {
          $_SESSION["toast_message"] = "Failed to update the Album";
          $_SESSION["toast_type"] = "alert-danger";
          header("Location: http://localhost/xampp/MARS/appolopublicschool.com/admin/album/edit.php?albumID={$id}");
          exit;
        }
      }
    }
  }
  public function updateAlbum($id, $data)
  {

    $isUpdated = $this->model->updateOneByColumnName("id", $id, $data);

    if ($isUpdated === false) {

      $_SESSION["toast_message"] = "Unable to Update Album.";
      $_SESSION["toast_type"] = "alert-danger";
      header("Location: http://localhost/xampp/MARS/appolopublicschool.com/admin/album/edit.php");
      exit;
    } else {

      $_SESSION["toast_message"] = "Successfully Updated Album";
      $_SESSION["toast_type"] = "alert-success";
      header("Location: http://localhost/xampp/MARS/appolopublicschool.com/admin/album/listing.php");
      exit;
    }
  }



  public function reorderAlbumsByYearId($newOrder, $year_id)
  {

    return $this->model->reorderAlbumsByYearId($newOrder, $year_id);
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
    include("FinancialYear.php");
    include("Gallery.php");
    $ids = $_POST["ids"];

    if (!empty($ids)) {
      foreach ($ids as $id) {
        // Retrieve album data to get the cover image filename


        $albumData = $this->model->findOneByColumnName("id", $id);
        $yearData = $fy_controller->findOne($albumData['year_id']);
        $albumImagesNamesArr = $gallery_controller->findAllAlbumImagesByAlbumId($albumData['id']);

        // var_dump($albumData);
        // var_dump($yearData);
        // var_dump($albumImagesNamesArr);


        if (
          !empty($albumData)
        ) {
          // Get the cover image filename
          $cover_image = $albumData['cover_image'];
          // var_dump($cover_image);
          // Delete the record from the album table
          if ($this->model->deleteById($id)) {
            // Check if the cover image exists and delete it from the uploads folder
            if (!empty($cover_image)) {
              global $root_path;
              $root_uploads_folder = $root_path . "/uploads";
              $cover_image_path = $root_uploads_folder . "/album/{$yearData['fiscal_year']}/cover_images/" . $cover_image;
              $original_cover_image_path = $root_uploads_folder . "/album/{$yearData['fiscal_year']}/cover_images/original/" . $cover_image;


              if (file_exists($cover_image_path)) {
                // var_dump($cover_image_path);

                unlink($cover_image_path); // Delete the image file
              }
              if (file_exists($original_cover_image_path)) {
                // var_dump($cover_image_path);

                unlink($original_cover_image_path); // Delete the image file
              }

              //Deleting the all the album images of the of the above deleted "album"
              foreach ($albumImagesNamesArr as $image) {
                // var_dump($image);
                $root_uploads_folder = $root_path . "/uploads";
                $album_image_path = $root_uploads_folder . "/album/{$yearData['fiscal_year']}/album_images/" . $image;
                $original_album_image_path = $root_uploads_folder . "/album/{$yearData['fiscal_year']}/album_images/original/" . $image;
                if (file_exists($album_image_path)) {
                  // var_dump($cover_image_path);
                  unlink($album_image_path); // Delete the image file
                }
                if (file_exists($original_album_image_path)) {
                  // var_dump($cover_image_path);
                  unlink($original_album_image_path); // Delete the image file
                }
              }
            }
          }
        }
      }




      $_SESSION["toast_message"] = "Successfully Deleted";
      $_SESSION["toast_type"] = "alert-success";
      exit();
    } else {
      $_SESSION["toast_message"] = "Select Atleast One Album";
      $_SESSION["toast_type"] = "alert-danger";
      exit();
    }
  }
  public  function moveImageToFolderX($id, $fy, $file_name, $file_tmp, $root_uplaods_folder, $destination_folder)
  {
    // Check if the "uploads" folder exists, if not, create it
    if (!file_exists($root_uplaods_folder)) {
      mkdir($root_uplaods_folder);
    }


    // Create "album" folder inside the "uploads" folder
    $album_folder = $root_uplaods_folder . "/album";
    if (!file_exists($album_folder)) {
      mkdir($album_folder);
    }

    // Create "fiscal_year" folder inside the "album" folder
    $fiscal_year_folder = $album_folder . "/" . $fy;
    if (!file_exists($fiscal_year_folder)) {
      mkdir($fiscal_year_folder);
    }

    // Create "cover_images" folder inside the "fiscal_year" folder
    $cover_images_folder = $fiscal_year_folder . "/cover_images";
    if (!file_exists($cover_images_folder)) {
      mkdir($cover_images_folder);
    }

    // Create "album_images" folder inside the "fiscal_year" folder
    $album_images_folder = $fiscal_year_folder . "/album_images";
    if (!file_exists($album_images_folder)) {
      mkdir($album_images_folder);
    }


    // Get file extension
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

    // New filename based on album ID
    $new_filename = $id . '.' . $file_extension;


    if ($destination_folder === "cover_images") {

      // Move the uploaded image to the "uploads" folder
      move_uploaded_file($file_tmp, $cover_images_folder . '/' . $new_filename);
      return $new_filename;
    }



    if ($destination_folder === "album_images") {

      // Move the uploaded image to the "uploads" folder
      move_uploaded_file($file_tmp, $album_images_folder . '/' . $new_filename);

      return $new_filename;
    }

    return false;
  }



  public function moveImageToFolder($id, $fy, $file_name, $file_tmp, $root_uplaods_folder, $destination_folder)
  {
    // Check if the "uploads" folder exists, if not, create it
    if (!file_exists($root_uplaods_folder)) {
      mkdir($root_uplaods_folder);
    }

    // Create "album" folder inside the "uploads" folder
    $album_folder = $root_uplaods_folder . "/album";
    if (!file_exists($album_folder)) {
      mkdir($album_folder);
    }

    // Create "fiscal_year" folder inside the "album" folder
    $fiscal_year_folder = $album_folder . "/" . $fy;
    if (!file_exists($fiscal_year_folder)) {
      mkdir($fiscal_year_folder);
    }

    // Create "cover_images" folder inside the "fiscal_year" folder
    $cover_images_folder = $fiscal_year_folder . "/cover_images";
    if (!file_exists($cover_images_folder)) {
      mkdir($cover_images_folder);
    }

    // Create "cover_images/original" folder inside the "fiscal_year" folder
    $original_cover_images_folder = $cover_images_folder . "/original";
    if (!file_exists($original_cover_images_folder)) {
      mkdir($original_cover_images_folder);
    }

    // Create "album_images" folder inside the "fiscal_year" folder
    $album_images_folder = $fiscal_year_folder . "/album_images";
    if (!file_exists($album_images_folder)) {
      mkdir($album_images_folder);
    }

    // Create "album_images/original" folder inside the "fiscal_year" folder
    $original_album_images_folder = $album_images_folder . "/original";
    if (!file_exists($original_album_images_folder)) {
      mkdir($original_album_images_folder);
    }

    // Generate a new filename based on the original image ID and the current size
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_filename = $id  . '.' . $file_extension;

    // Define the path to save the original image
    $original_image_path = null;
    $cropped_image_folder = null;

    if ($destination_folder == "cover_images") {
      $original_image_path = $original_cover_images_folder . '/' . $new_filename;
      $cropped_image_folder = $cover_images_folder;
    } elseif ($destination_folder == "album_images") {
      $original_image_path = $original_album_images_folder . '/' . $new_filename;
      $cropped_image_folder = $cover_images_folder;
    }

    // Save the original image to the "original" folder
    move_uploaded_file($file_tmp, $original_image_path);

    // Get the dimensions of the original image
    list($width_orig, $height_orig) = getimagesize($original_image_path);

    // Calculate dimensions for resizing while preserving aspect ratio
    $aspect_ratio = $width_orig / $height_orig;
    $new_width = 650;
    $new_height = $new_width / $aspect_ratio;

    // Create a true color image for cropping
    $cropped_image = imagecreatetruecolor($new_width, $new_height);

    // Create an image resource from the original image
    $source_image = imagecreatefromstring(file_get_contents($original_image_path));

    // Crop the image to the desired dimensions while preserving aspect ratio
    $success = imagecopyresampled($cropped_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);

    // Define the path to save the cropped image
    $cropped_image_path = $cropped_image_folder . '/' . $new_filename;

    // Determine the appropriate image format based on the original extension
    switch ($file_extension) {
      case 'png':
        imagepng($cropped_image, $cropped_image_path); // Save as PNG format
        break;
      case 'jpg':
      case 'jpeg':
        imagejpeg($cropped_image, $cropped_image_path); // Save as JPEG format
        break;
      default:
        // Unsupported image format, handle appropriately (here we assume PNG)
        imagepng($cropped_image, $cropped_image_path); // Save as PNG format
    }

    // Destroy the image resources to free up memory
    imagedestroy($cropped_image);
    imagedestroy($source_image);

    // Return the filename of the cropped image
    return $new_filename;
  }
}

//{--------------CALLING CONTROLLER METHODS--------------

$album_controller = new Album;

$albums = null;
$fyID = null;
$json_albums = null;
$numOfAlbums = null;
$albums = $album_controller->findAll();
$numOfAlbums = count($albums);


if (strpos($current_path, "album/listing.php") != false) {

  // echo "<pre> <br>";
  // echo "LISTING";
  // exit;

  if (!empty($_SERVER['QUERY_STRING'])) {
    $fyID = explode("=", $_SERVER['QUERY_STRING'])[1];
    if ($fyID == "all") {
      $albums = $album_controller->findAll();
      $numOfAlbums = count($albums);
    } else {
      $albums = $album_controller->findAll("year_id", $fyID);
      $numOfAlbums = count($albums);
    }
  } else {
    $albums = $album_controller->findAll();
    $numOfAlbums = count($albums);
  }


  $json_albums = json_encode($albums);
  if (!empty($albums)) {


    foreach ($albums as &$item) {
      global $fy_controller;
      $fy = $fy_controller->findOne($item['year_id'])['fiscal_year'];
      if ($fy) {
        $item['fy_name'] = $fy;
      }
    }

    unset($item);
  }
}






//{----------------------------
$albumId = null;
$albumData = null;
$yearData = null;

if (strpos($current_path, "album/show.php?albumID=") != false) {


  $albumId = explode("=", $_SERVER['QUERY_STRING'])[1];
  $albumData = $album_controller->findOne($albumId);
  $yearData = $fy_controller->findOne($albumData['year_id']);
}

$isAlbumEditPage = false;


if (strpos($current_path, "album/edit.php") != false) {





  if (strpos($current_path, "album/edit.php?albumID=") != false) {


    $isAlbumEditPage = true;
    $albumId = explode("=", $_SERVER['QUERY_STRING'])[1];
    $albumData = $album_controller->findOne($albumId);
    $yearData = $fy_controller->findOne($albumData['year_id']);


    if (isset($_POST["add_album"])) {

      // Retrieve data from the form submission
      $year_id = $_POST['year_id'];
      $name = $_POST['name'];
      $status = $_POST['status'];


      // Prepare the data to be inserted into the database
      $data = ["year_id" => $year_id, "name" => $name, "status" => $status];


      $album_controller->updateAlbumAndAddCoverImage($albumId, $data, $year_id);

      exit;
    }
  } else {

    $isAlbumEditPage = false;

    if (isset($_POST["add_album"])) {

      // Retrieve data from the form submission
      $year_id = $_POST['year_id'];
      $name = $_POST['name'];
      $status = $_POST['status'];


      // Prepare the data to be inserted into the database
      $data = ["year_id" => $year_id, "name" => $name, "status" => $status];


      $album_controller->createAlbumAndAddCoverImage($data, $year_id);
    }
  }
}







if (isset($_GET['album_action'])) {
  if ($_GET['album_action'] == 'activate') {
    $album_controller->activate();
  } else if ($_GET['album_action'] == 'block') {
    $album_controller->block();
  } else if ($_GET['album_action'] == 'delete') {

    $album_controller->delete();
  }
}


//--------------------------------------------------}


//{--------------REORDERING --------------
if (isset($_REQUEST['action']) and $_REQUEST['action'] == "updateSortedRows") {

  $year_id = $_REQUEST["year_id"];
  $newOrder = explode(",", $_REQUEST['sortOrder']);
  // [7,8,6,5,4,3,2,1]

  $album_controller->reorderAlbumsByYearId($newOrder, $year_id);
}
//--------------------------------------------------}