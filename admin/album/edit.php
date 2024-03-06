<?php
$base_url = "http://localhost/xampp/MARS/appolopublicschool.com/";

session_start();
if (!isset($_SESSION["user"])) {
  header("Location: {$base_url}admin");
  exit();
}

include("../controller/Login.php");
include("../controller/FinancialYear.php");
include("../controller/Album.php");
include("../controller/Gallery.php");


include("../inc/header.php");
include("../inc/leftnav.php");


?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <!-- <h1>Add Album</h1> -->
        </div>
        <div class="col-sm-12">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Album</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">


      <div class="row">


        <div class="col-sm-12">
          <!-- general form elements disabled -->
          <div class="card card-warning">
            <div class="card-header">

              <h3 class="card-title"> <?= ($isAlbumEditPage == true)  ? "Edit Album" : "Add New Album"    ?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <!-- if your form logic in the same page don't use mention the action attribute in the form -->
              <form action="" method="post" enctype="multipart/form-data" class="">
                <div class="row">
                  <div class="col-sm-12">


                    <!-- select -->
                    <div class="form-group">
                      <label>Select Fiscal Year</label>
                      <?php if (!empty($_SESSION['album_data']['year_id'])) : ?>
                        <select class="form-control" id="year_id" name="year_id" required>
                          <option value=""></option>
                          <?php foreach ($years as $year) : ?>
                            <option value="<?= $year['id']; ?>" <?= $_SESSION['album_data']["year_id"] == $year['id'] ? "selected" : ""; ?>>
                              <?= $year['fiscal_year']; ?></option>
                          <?php endforeach; ?>

                        </select>
                      <?php elseif ($isAlbumEditPage == true) : ?>
                        <select class="form-control" id="year_id" name="year_id" required readonly>
                          <option value="<?= $yearData['id'] ?>"><?= $yearData['fiscal_year'] ?></option>


                        </select>

                      <?php else : ?>


                        <select class="form-control" id="year_id" name="year_id" required>
                          <option value=""></option>
                          <?php foreach ($years as $year) : ?>
                            <option value="<?= $year['id']; ?>" <?= $albumData["year_id"] == $year['id'] ? "selected" : ""; ?>>
                              <?= $year['fiscal_year']; ?></option>
                          <?php endforeach; ?>

                        </select>
                      <?php endif; ?>



                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <!-- text input -->
                    <div class="form-group">
                      <label for="name">Album Name</label>
                      <?php if (!empty($_SESSION['album_data']['name'])) : ?>
                        <input type="text" class="form-control" placeholder="Enter Album Name..." name="name" id="name" value="<?= !empty($_SESSION['album_data']['name']) ? $_SESSION['album_data']['name'] : ""   ?>" required />
                      <?php else : ?>
                        <input type="text" class="form-control" placeholder="Enter Album Name..." name="name" id="name" value="<?= $albumData != null ? $albumData["name"] : ""   ?>" required />
                      <?php endif; ?>

                    </div>
                  </div>
                </div>



                <div class="row">
                  <div class="col-sm-12">
                    <!-- text input -->
                    <div class="form-group">
                      <label for="cover_image">Cover Image </label>

                      <?php if ($isAlbumEditPage == true) : ?>
                        <div class="d-flex align-items-center">
                          <div class="custom-file mr-4" style="max-width: 500px">
                            <input type="file" class="custom-file-input" id="cover_image" name="cover_image" />
                            <label class="custom-file-label" for="exampleInputFile"><?= $albumData["cover_image"] ?></label>
                          </div>
                          <div id="coverImagePreviewContainer" class="d-flex justify-content-center align-items-center" style="height: 38px; width: 41px">
                            <img src="<?= "../../uploads/album/" . $yearData["fiscal_year"] . "/cover_images/" . $albumData["cover_image"] ?>" alt="" style="height: 38px; width: 41px;object-fit:cover;">

                          </div>

                        </div>
                        <small class="text-muted mt-1 mb-2"> &#40; only
                          PNG/JPEG/JPG image formats less than
                          2MB
                          are allowed &#41;</small>
                      <?php else : ?>
                        <div class="d-flex align-items-center">
                          <div class="custom-file mr-4" style="max-width: 500px">
                            <input type="file" class="custom-file-input" id="cover_image" name="cover_image" accept="image/png, image/jpeg,image/jpg" />
                            <label class="custom-file-label" for="exampleInputFile">Choose image</label>
                          </div>
                          <div id="coverImagePreviewContainer" class="d-flex justify-content-center align-items-center" style="height: 38px; width: 41px">


                          </div>

                        </div>
                        <small class="text-muted mt-1 mb-2"> &#40; only
                          PNG/JPEG/JPG image formats less than
                          2MB
                          are allowed &#41;</small>
                      <?php endif; ?>

                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12">
                    <!-- radio -->
                    <label class="form-check-label">Select Status</label>
                    <div class="form-group  ">
                      <?php if ((!empty($_SESSION['album_data']))) : ?>
                        <div class="form-check mr-5">
                          <input class="form-check-input" type="radio" name="status" required value="1" <?= $_SESSION['album_data']['status'] === "1" ? "checked" : ""    ?>>
                          <label class=" form-check-label">Active</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="status" required value="0" <?= $_SESSION['album_data']['status'] === "0" ? "checked" : ""    ?>>
                          <label class="form-check-label">Block</label>
                        </div>

                      <?php elseif ($isAlbumEditPage == true) : ?>
                        <div class="form-check mr-5">
                          <input class="form-check-input" type="radio" name="status" required value="1" <?= ($albumData != null && $albumData["status"] == 1) ? "checked" : ""    ?>>
                          <label class=" form-check-label">Active</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="status" required value="0" <?= ($albumData != null && $albumData["status"] == 0) ? "checked" : ""    ?>>
                          <label class="form-check-label">Block</label>
                        </div>
                      <?php else : ?>
                        <div class="form-check mr-5">
                          <input class="form-check-input" type="radio" name="status" required value="1" checked>
                          <label class=" form-check-label">Active</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="status" required value="0">
                          <label class="form-check-label">Block</label>
                        </div>
                      <?php endif; ?>

                    </div>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary" name="add_album">
                  Submit
                </button>
              </form>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->



<?php
include("../inc/footer.php")
?>

<script>
  $(document).ready(function() {
    // Add event listener for image input change
    $('#cover_image').change(function(event) {





      // Get the selected file
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const imagePreviewContainer = $('#coverImagePreviewContainer');
          const imgElement = $('<img>').attr('src', e.target.result)
            .addClass('img-fluid ')
            .css({
              width: '38px',
              height: '38px',
              objectFit: 'cover'
            });
          imagePreviewContainer.empty(); // Clear previous image previews
          imagePreviewContainer.append(imgElement);
        };
        reader.readAsDataURL(file);
      }
    });




  })
</script>