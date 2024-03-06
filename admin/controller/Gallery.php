<?php


$root_path = str_replace("\\", "/", dirname(dirname(__DIR__)));
require_once $root_path . "/admin/model/GalleryModel.php";


$base_url = "http://localhost/xampp/MARS/appolopublicschool.com/";


$current_path = $_SERVER['REQUEST_URI'];
class Gallery
{

  public $model;

  public function __construct()
  {
    $this->model = new GalleryModel();
  }

  public function findAllAlbumImagesByAlbumId($album_id)
  {

    return $this->model->findAllAlbumImagesByAlbumId($album_id);
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






  public function createGalleryAndAddGalleryImage($formattedData, $albumId)
  {

    foreach ($formattedData as $item) {
      if (!empty($item['album_image']['name'])) {
        // Get the file name, type, and size
        $file_name = $item['album_image']['name'];
        $file_type = $item['album_image']['type'];
        $file_size = $item['album_image']['size'];
        $file_tmp = $item['album_image']['tmp_name'];

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

          $_SESSION["gallery_data"] = $formattedData;
          $_SESSION["toast_message"] = "Only PNG, JPEG, and JPG Image types are allowed.";
          $_SESSION["toast_type"] = "alert-danger";
          global $base_url;
          header("Location: {$base_url}admin/gallery/add.php?albumID={$albumId}");
          exit;
        } elseif ($file_size > $max_file_size) {
          // File size exceeds the limit
          // Handle the error or display a message
          // echo "Error: File size should not exceed 2MB.";
          $_SESSION["gallery_data"] = $formattedData;
          $_SESSION["toast_message"] = "Image size should not exceed 2MB.";
          $_SESSION["toast_type"] = "alert-danger";
          global $base_url;
          header("Location: {$base_url}admin/gallery/add.php?albumID={$albumId}");

          exit;
        } elseif ($width < 650 || $height < 400) {
          $_SESSION["gallery_data"] =  $formattedData;
          $_SESSION["toast_message"] = "Minimum image dimensions required is 650 X 400 pixels";
          $_SESSION["toast_type"] = "alert-danger";
          global $base_url;
          header("Location: {$base_url}admin/gallery/add.php?albumID={$albumId}");
          exit;
        }
      }
    }

    // Iterate over each formatted image data
    foreach ($formattedData as $item) {






      // Prepare data for creating a new image record without the image itself
      $dataForImageWithoutImage = ["album_id" => $item["album_id"], "name" => $item["album_image_name"]];

      // Retrieve year ID and image details from the formatted data
      $year_id = $item["year_id"];
      $file_name = $item['album_image']['name'];
      $file_tmp = $item['album_image']['tmp_name'];

      // Create a new image record in the database without the actual image
      $inserted_album_image_id = $this->model->createOne($dataForImageWithoutImage);

      // Check if the image record creation was successful
      if (!$inserted_album_image_id) {
        $_SESSION["toast_message"] = "Failed to add Images to Album ";
        $_SESSION["toast_type"] = "alert-danger";
        // Redirect the user to the welcome page after successfully adding album photos
        global $base_url;
        header("Location: {$base_url}admin/gallery/edit.php");
        exit();
      }

      // Retrieve the fiscal year from the year table based on the year_id
      global $fy_controller;
      $yearRecord = $fy_controller->findOne($year_id);

      // Initialize fiscal year variable
      $fy = null;

      // Check if the fiscal year record exists
      if ($yearRecord) {
        // Assign the fiscal year to the variable
        $fy = $yearRecord["fiscal_year"];
      }

      global $root_path;
      // Move the uploaded image to the appropriate folder
      $movedImageName = $this->moveImageToFolder($inserted_album_image_id, $fy, $file_name, $file_tmp, $root_path . "/uploads", "album_images");

      // Prepare data for updating the image table with the actual image name
      $dataForUpdate = ["album_image" => $movedImageName];

      // Update the image table with the actual image name
      $isTableUpdated = $this->model->updateOneByColumnName("id", $inserted_album_image_id, $dataForUpdate);

      // Check if the table update was successful
      if (!$isTableUpdated) {
        $_SESSION["toast_message"] = "Failed to add Images to Album ";
        $_SESSION["toast_type"] = "alert-danger";
        // Redirect the user to the welcome page after successfully adding album photos
        global $base_url;
        header("Location: {$base_url}admin/gallery/edit.php");
        exit();
      }
    }

    $_SESSION["toast_message"] = "Successfully added Images to Album ";
    $_SESSION["toast_type"] = "alert-success";
    // Redirect the user to the welcome page after successfully adding album photos
    global $base_url;
    header("Location: {$base_url}admin/album/show.php?albumID={$albumId}#show_AlbumImages");
    exit();
  }


  public function createGalleryAndAddGalleryVideo($formattedData, $albumId)
  {


    try {
      // Begin transaction
      $this->model->conn->beginTransaction();

      foreach ($formattedData as $data) {
        // Create a new image record in the database without the actual image
        $inserted_album_image_id = $this->model->createOneAlbumImage($data);

        // Check if the image record creation was successful
        if (!$inserted_album_image_id) {
          // Rollback transaction
          $this->model->conn->rollback();

          $_SESSION["toast_message"] = "Failed to add Video to Album ";
          $_SESSION["toast_type"] = "alert-danger";
          // Redirect the user to the welcome page after successfully adding album photos
          global $base_url; //
          header("Location: {$base_url}admin/gallery/add.php?albumID=$albumId");
          // exit();
        }
      }

      // Commit transaction
      $this->model->conn->commit();
      $_SESSION["toast_message"] = "Successfully added Videos to Album ";
      $_SESSION["toast_type"] = "alert-success";
      // Redirect the user to the welcome page after successfully adding album photos
      global $base_url;
      header("Location: {$base_url}admin/album/show.php?albumID={$albumId}#show_AlbumImages");
      exit();
    } catch (Exception $e) {
      // Rollback transaction
      $this->model->conn->rollback();

      // Handle exception
      $_SESSION["toast_message"] = "An error occurred while adding images to the album.";
      $_SESSION["toast_type"] = "alert-danger";
      // Redirect the user to the welcome page after encountering an error
      global $base_url;
      header("Location: {$base_url}admin/gallery/add.php?albumID=$albumId");
      exit();
    }
  }

  public function updateGalleryAndAddGalleryImage($id, $data, $year_id, $albumId)
  {



    $file_name = $_FILES['album_image']['name'];
    $file_tmp = $_FILES['album_image']['tmp_name'];


    // Check if the file has been uploaded
    if (!empty($file_name)) {
      // Get the file name, type, and size
      $file_name = $_FILES['album_image']['name'];
      $file_type = $_FILES['album_image']['type'];
      $file_size = $_FILES['album_image']['size'];

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

        $_SESSION["gallery_data"] = $data;
        $_SESSION["toast_message"] = "Only PNG, JPEG, and JPG Image types are allowed.";
        $_SESSION["toast_type"] = "alert-danger";
        global $base_url;
        header("Location: {$base_url}admin/gallery/edit.php?id=$id");
        exit;
      } elseif ($file_size > $max_file_size) {
        // File size exceeds the limit
        // Handle the error or display a message
        // echo "Error: File size should not exceed 2MB.";
        $_SESSION["gallery_data"] = $data;
        $_SESSION["toast_message"] = "Image size should not exceed 2MB.";
        $_SESSION["toast_type"] = "alert-danger";
        global $base_url;
        header("Location: {$base_url}admin/gallery/edit.php?id=$id");
        exit;
      } elseif ($width < 650 || $height < 400) {
        $_SESSION["gallery_data"] =  $data;
        $_SESSION["toast_message"] = "Minimum image dimensions required is 650 X 400 pixels";
        $_SESSION["toast_type"] = "alert-danger";
        global $base_url;
        header("Location: {$base_url}admin/gallery/edit.php?id=$id");
        exit;
      } else {

        $isUpdated = $this->model->updateOneByColumnName("id", $id, $data);


        // Check if the gallery was successfully inserted
        if (isset($isUpdated)) {
          // Retrieve the uploaded cover image details
          $file_name = $_FILES['album_image']['name'];
          $file_tmp = $_FILES['album_image']['tmp_name'];
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
              global $base_url;
              header("Location: {$base_url}admin/gallery/edit.php");
              exit;
            }



            global $root_path;
            // Move the uploaded cover image to the appropriate folder
            $movedImageName =  $this->moveImageToFolder($id, $fy_year, $file_name, $file_tmp, $root_path . "/uploads", "album_images");


            if ($movedImageName == false) {
              $_SESSION["toast_message"] = "Gallery updated successfully but failed to add the Galler image";
              $_SESSION["toast_type"] = "alert-danger";
              global $base_url;
              header("Location: {$base_url}admin/gallery/edit.php");
              exit;
            }
            // Prepare data for updating the gallery table with the cover image name
            $data = ["album_image" => $movedImageName];

            // Update the gallery table with the cover image name
            $isGalleryImageUpdated = $this->model->updateOneByColumnName("id", $id, $data);

            // Check if the table update was successful
            if (!$isGalleryImageUpdated) {
              // Display error message if the table update failed
              $_SESSION["toast_message"] = "Gallery updated successfully but failed to add the Galler image";
              $_SESSION["toast_type"] = "alert-danger";
              global $base_url;
              header("Location: {$base_url}admin/gallery/edit.php?id=$id");
              exit;
            }
          }

          $_SESSION["toast_message"] = "Successfully updated the Album Image";
          $_SESSION["toast_type"] = "alert-success";

          // Redirect the user to the welcome page after successful gallery creation
          global $base_url;
          header("Location: {$base_url}admin/album/show.php?albumID={$albumId}#show_AlbumImages");
          exit();
        } else {
          $_SESSION["toast_message"] = "Failed to update the Gallery";
          $_SESSION["toast_type"] = "alert-danger";
          global $base_url;
          header("Location: {$base_url}admin/gallery/edit.php?id=$id");
          exit;
        }
      }
    }
  }

  public function updateGalleryAndAddGalleryVideo($data, $imageID)
  {


    $isUpdated = $this->model->updateOneByColumnName("id", $imageID, $data);

    if ($isUpdated) {
      // Handle exception
      $_SESSION["toast_message"] = "Successfully Updated.";
      $_SESSION["toast_type"] = "alert-success";
      // Redirect the user to the welcome page after encountering an error
      global $base_url;
      header("Location: {$base_url}admin/album/show.php?albumID={$data['album_id']}#show_AlbumImages");
      exit();
    } else {
      // Handle exception
      $_SESSION["toast_message"] = "Failed to Update.";
      $_SESSION["toast_type"] = "alert-danger";
      // Redirect the user to the welcome page after encountering an error
      global $base_url;
      header("Location: {$base_url}admin/gallery/edit.php?imageID=$imageID");
      exit();
    }
  }

  public function updateGalleryImage($id, $data)
  {

    $isUpdated = $this->model->updateOneByColumnName("id", $id, $data);

    if ($isUpdated === false) {

      $_SESSION["toast_message"] = "Unable to Update Gallery.";
      $_SESSION["toast_type"] = "alert-danger";
      global $base_url;
      header("Location: {$base_url}admin/gallery/edit.php?id=$id");
      exit;
    } else {

      $_SESSION["toast_message"] = "Successfully Updated Gallery";
      $_SESSION["toast_type"] = "alert-success";
      global $base_url;
      header("Location: {$base_url}admin/gallery/listing.php");
      exit;
    }
  }

  public function reorderAlbumImagesByAlbumId($newOrder, $album_id)
  {

    return $this->model->reorderAlbumImagesByAlbumId($newOrder, $album_id);
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
    include("Album.php");
    $ids = $_POST["ids"];

    if (!empty($ids)) {
      foreach ($ids as $id) {
        // Retrieve album data to get the cover image filename
        $albumImageData = $this->model->findOneByColumnName('id', $id);
        $albumData = $album_controller->findOne($albumImageData['album_id']);
        $yearData = $fy_controller->findOne($albumData['year_id']);
        if (
          !empty($albumImageData)
        ) {
          // Get the cover image filename
          $album_image = $albumImageData['album_image'];


          // Delete the record from the album table
          if ($this->model->deleteById($id)) {
            // Check if the cover image exists and delete it from the uploads folder
            if (!empty($album_image)) {
              global $root_path;
              $root_uploads_folder = $root_path . "/uploads";
              $album_image_path = $root_uploads_folder . "/album/{$yearData['fiscal_year']}/album_images/" . $album_image;
              $original_album_image_path = $root_uploads_folder . "/album/{$yearData['fiscal_year']}/album_images/original/" . $album_image;

              if (file_exists($album_image_path)) {
                unlink($album_image_path); // Delete the image file
              }

              if (file_exists($original_album_image_path)) {
                unlink($original_album_image_path); // Delete the image file
              }
            }
          }
        }
      }
      $_SESSION["toast_message"] = "Sucessfully Deleted";
      $_SESSION["toast_type"] = "alert-success";
    } else {
      $_SESSION["toast_message"] = "Select Atleast One Album Image";
      $_SESSION["toast_type"] = "alert-danger";
    }
    exit();
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
      $cropped_image_folder = $album_images_folder;
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
$gallery_controller = new Gallery;



$galleryData = $gallery_controller->findAll();
$num_of_album_images = count($galleryData);








$album_data = null;
$album_images = null;
$year_data = null;



if (strpos($current_path, "gallery/listing.php") != false) {
  $album_images = $gallery_controller->findAll();

  $albums = $album_controller->findAll();
}





$galleryImageId = null;
$galleryImageData = null;
$yearDataInGalleryPage = null;
$albumDataInGalleryPage = null;



if (strpos($current_path, "gallery/edit.php") != false) {


  if (strpos($current_path, "gallery/edit.php?imageID=") != false) {

    $galleryImageId = explode("=", $_SERVER['QUERY_STRING'])[1];
    $galleryImageData = $gallery_controller->findOne($galleryImageId);
    $albumDataInGalleryPage = $album_controller->findOne($galleryImageData['album_id']);
    $yearDataInGalleryPage = $fy_controller->findOne($albumDataInGalleryPage['year_id']);



    if (isset($_POST["update_gallery_image"])) {

      if ($galleryImageData['type'] == '1') {
        // Retrieve data from the form submission
        $year_id = $_POST['year_id'];
        $album_id = $_POST['album_id'];
        $name = $_POST['name'];
        $status = $_POST['status'];

        // Prepare the data to be inserted into the database
        $data = ["album_id" => $album_id,  "name" => $name, "status" => $status];


        $gallery_controller->updateGalleryAndAddGalleryImage($galleryImageId, $data, $year_id, $album_id);

        exit;
      } else {


        // Retrieve data from the form submission
        $year_id = $_POST['year_id'];
        $album_id = $_POST['album_id'];
        $album_video_title = $_POST['album_video_title'];
        $album_video_code = $_POST['album_video_code'];
        $status = $_POST['status'];
        // Prepare the data to be inserted into the database
        $data = ["album_id" => $album_id, "album_image" => $album_video_code,  "name" => $album_video_title, "status" => $status];


        $gallery_controller->updateGalleryAndAddGalleryVideo($data,  $galleryImageId);

        exit;
      }
    }
  } else {
    echo "Page NOT Found <br>";
    exit;
  }
}




$isAddMoreImagesPage = false;


if (strpos($current_path, "gallery/add.php") != false) {


  if (strpos($current_path, "gallery/add.php?albumID=") != false) {
    $isAddMoreImagesPage = true;
    $albumId = explode("=", $_SERVER['QUERY_STRING'])[1];
    $albumData = $album_controller->findOne($albumId);
    $yearData = $fy_controller->findOne($albumData['year_id']);




    if (isset($_POST["add_gallery_image"])) {
      // Retrieve data from the form submission
      $data1 = $_POST; // Contains album data

      $data2 = $_FILES; // Contains image files

      if ($data1['type'] == "1") {
        // Initialize an array to store formatted data for each image
        $formattedData = array();

        // Iterate over album image names and format data
        foreach ($data1['album_image_name'] as $index => $imageName) {
          // Format data for each image
          $formattedData[] = array(
            'year_id' => $data1['year_id'], // Year ID associated with the album
            'album_id' => $data1['album_id'], // Album ID where the image belongs
            'type' => $data1['type'], // Album ID where the image belongs
            'status' => $data1['status'], // Album ID where the image belongs
            'album_image_name' => $imageName, // Name of the image
            'album_image' => array(
              'name' => $data2['album_image']['name'][$index], // Original filename of the image
              'type' => $data2['album_image']['type'][$index], // Mime type of the image
              'tmp_name' => $data2['album_image']['tmp_name'][$index], // Temporary filename of the image
              'error' => $data2['album_image']['error'][$index], // Error code of the image upload
              'size' => $data2['album_image']['size'][$index] // Size of the image
            )
          );
        }




        $gallery_controller->createGalleryAndAddGalleryImage($formattedData,    $albumId);
      } else {




        // Initialize an array to store formatted data for each image
        $formattedData = array();

        // Iterate over album image names and format data
        foreach ($data1['album_video_title'] as $index => $album_video_title) {
          // Format data for each image
          $formattedData[] = array(
            'album_id' => $data1['album_id'], // Album ID where the image belongs
            'type' => $data1['type'], // Album ID where the image belongs
            'name' => $album_video_title, // Name of the image
            'album_image' => $data1["album_video_code"][$index], // Name of the image
            'status' => $data1['status'], // Album ID where the image belongs

          );
        }




        $gallery_controller->createGalleryAndAddGalleryVideo($formattedData,    $albumId);
      }
    }
  } else {
    $albums = $album_controller->findAll();
    $json_albums = json_encode($albums);

    if (isset($_POST["add_gallery_image"])) {

      // // Retrieve data from the form submission
      // $data1 = $_POST; // Contains album data
      // $data2 = $_FILES; // Contains image files

      // // Initialize an array to store formatted data for each image
      // $formattedData = array();

      // // Iterate over album image names and format data
      // foreach ($data1['album_image_name'] as $index => $imageName) {
      //   // Format data for each image
      //   $formattedData[] = array(
      //     'year_id' => $data1['year_id'], // Year ID associated with the album
      //     'album_id' => $data1['album_id'], // Album ID where the image belongs
      //     'album_image_name' => $imageName, // Name of the image
      //     'status' => $data1['status'], // Name of the image
      //     'album_image' => array(
      //       'name' => $data2['album_image']['name'][$index], // Original filename of the image
      //       'type' => $data2['album_image']['type'][$index], // Mime type of the image
      //       'tmp_name' => $data2['album_image']['tmp_name'][$index], // Temporary filename of the image
      //       'error' => $data2['album_image']['error'][$index], // Error code of the image upload
      //       'size' => $data2['album_image']['size'][$index] // Size of the image
      //     )
      //   );
      // }




      // $gallery_controller->createGalleryAndAddGalleryImage($formattedData, $data1['album_id']);


      $data1 = $_POST; // Contains album data
      $data2 = $_FILES; // Contains image files
      if ($data1['type'] == "1") {
        // Initialize an array to store formatted data for each image
        $formattedData = array();

        // Iterate over album image names and format data
        foreach ($data1['album_image_name'] as $index => $imageName) {
          // Format data for each image
          $formattedData[] = array(
            'year_id' => $data1['year_id'], // Year ID associated with the album
            'album_id' => $data1['album_id'], // Album ID where the image belongs
            'type' => $data1['type'], // Album ID where the image belongs
            'status' => $data1['status'], // Album ID where the image belongs
            'album_image_name' => $imageName, // Name of the image
            'album_image' => array(
              'name' => $data2['album_image']['name'][$index], // Original filename of the image
              'type' => $data2['album_image']['type'][$index], // Mime type of the image
              'tmp_name' => $data2['album_image']['tmp_name'][$index], // Temporary filename of the image
              'error' => $data2['album_image']['error'][$index], // Error code of the image upload
              'size' => $data2['album_image']['size'][$index] // Size of the image
            )
          );
        }




        $gallery_controller->createGalleryAndAddGalleryImage($formattedData,    $albumId);
      } else {




        // Initialize an array to store formatted data for each image
        $formattedData = array();

        // Iterate over album image names and format data
        foreach ($data1['album_video_title'] as $index => $album_video_title) {
          // Format data for each image
          $formattedData[] = array(
            'album_id' => $data1['album_id'], // Album ID where the image belongs
            'type' => $data1['type'], // Album ID where the image belongs
            'name' => $album_video_title, // Name of the image
            'album_image' => $data1["album_video_code"][$index], // Name of the image
            'status' => $data1['status'], // Album ID where the image belongs

          );
        }




        $gallery_controller->createGalleryAndAddGalleryVideo($formattedData,    $albumId);
      }
    }
  }
}






//for gallery/listing.php page
if (isset($_GET['gallery_action'])) {
  if ($_GET['gallery_action'] == 'activate') {
    $gallery_controller->activate();
  } else if ($_GET['gallery_action'] == 'block') {
    $gallery_controller->block();
  } else if ($_GET['gallery_action'] == 'delete') {
    $gallery_controller->delete();
  }
}
//--------------------------------------------------}

$numOfAlbumImages = null;
$albumImagesByAlbumId = null;
if (strpos($current_path, "album/show.php?albumID=") != false) {


  $albumImagesByAlbumId = $gallery_controller->findAll("album_id", $albumId);
  $numOfAlbumImages = count($albumImagesByAlbumId);
}
//{--------------REORDERING of ALBUM IMAGES--------------



if (isset($_REQUEST['action']) and $_REQUEST['action'] == "updateSortedRows") {

  $album_id = $_REQUEST["album_id"];
  $newOrder = explode(",", $_REQUEST['sortOrder']);
  // [7,8,6,5,4,3,2,1]

  $gallery_controller->reorderAlbumImagesByAlbumId($newOrder, $album_id);
}
  //--------------------------------------------------}