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
          <!-- <h1>Add Gallery</h1> -->
        </div>
        <div class="col-sm-12">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Gallery</li>
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
              <h3 class="card-title"> <?= $isAddMoreImagesPage == true ? "Add More Images" : "Add Images"    ?></h3>
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


                      <?php if ($isAddMoreImagesPage) : ?>
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
                    <!-- select -->
                    <div class="form-group">
                      <label>Select Album</label>
                      <?php if ($isAddMoreImagesPage) : ?>
                        <select class="form-control" id="album_id" name="album_id" readonly>
                          <option value="<?= $albumData['id'] ?>">
                            <?= $albumData["name"] ?> </option>


                        </select>
                      <?php else : ?>
                        <select class="form-control" id="album_id" name="album_id">
                          <option value="">
                          </option>
                          <?php foreach ($albums as $album) : ?>

                            <option value="<?= $album["id"] ?>">
                              <?= $album["name"] ?>
                            </option>
                          <?php endforeach; ?>

                        </select>
                      <?php endif; ?>

                    </div>
                  </div>
                </div>



                <div class="row mt-2">
                  <div class="col-sm-12 d-flex">
                    <!-- radio -->
                    <label class=" mr-4">Select Multimedia Type : </label>
                    <div class="form-group  ">
                      <?php if (!empty($_SESSION['gallery_data'][0])) : ?>

                        <div class="form-check-inline mr-2">
                          <input class="form-check-input" type="radio" name="type" required value="1" <?= $_SESSION['gallery_data'][0]['type'] === "1" ? "checked" : ""    ?>>
                          <label class=" form-check-label">Image</label>
                        </div>
                        <div class="form-check-inline">
                          <input class="form-check-input" type="radio" name="type" required value="0" <?= $_SESSION['gallery_data'][0]['type'] === "0" ? "checked" : ""    ?>>
                          <label class="form-check-label">Video</label>
                        </div>


                      <?php else : ?>
                        <div class="form-check-inline  mr-2">
                          <input class="form-check-input x" type="radio" name="type" required value="1" checked>
                          <label class=" form-check-label">Image</label>
                        </div>
                        <div class="form-check-inline">
                          <input class="form-check-input" type="radio" name="type" required value="0">
                          <label class="form-check-label">Video</label>
                        </div>
                      <?php endif; ?>

                    </div>
                  </div>
                </div>

                <div class="row " id="uploadImagesContainer">
                  <div class="col-sm-12">
                    <label>Upload Images</label>
                    <div id="imageUploadFields" class="imageUploadFields">
                      <div class="row w-100 image-upload-field">
                        <div class="col-sm-4">
                          <div class="form-group">
                            <?php if (!empty($_SESSION['gallery_data']['name'])) : ?>
                              <input type="text" name="album_image_name[]" class="form-control" value="<?= !empty($_SESSION['gallery_data']['name']) ? $_SESSION['gallery_data']['name'] : ""  ?>" placeholder="Enter Image Name..." />
                            <?php else : ?>
                              <input type="text" name="album_image_name[]" class="form-control" placeholder="Enter Image Name..." />
                            <?php endif; ?>

                          </div>
                        </div>

                        <div class="col-sm-6">
                          <div class="d-flex custom-file align-items-center">
                            <input type="file" class="album_image1 custom-file-input" id="album_image" name="album_image[]" accept="image/png, image/jpeg,image/jpg" />
                            <label class="custom-file-label" for="album_image">Choose image</label>
                          </div>
                        </div>
                        <div class="col-sm-1">
                          <div class="d-flex justify-content-center align-items-center galleryImagePreviewContainer" style="height: 38px; width: 41px">
                          </div>
                        </div>
                        <div class="col-sm-1">
                          <button class="btn btn-danger delete-image-field ms-3">
                            <i class="fa-solid fa-trash"></i>
                          </button>
                        </div>
                      </div>

                    </div>

                    <small class="text-muted mt-1 mb-2"> &#40; only
                      PNG/JPEG/JPG image formats less than
                      2MB
                      are allowed &#41;</small>
                  </div>
                  <div class="col-sm-12 mt-2">
                    <div role="button" id="addImageButton" class="col-sm-12 border text-muted text-center rounded-pill">
                      Add New Line
                    </div>

                  </div>
                </div>

                <div class="row" id="uploadVideosContainer">
                  <div class="col-sm-12">
                    <label>Upload Videos</label>
                    <div id="videoUploadFields" class="videoUploadFields">
                      <div class="row w-100 video-upload-field">
                        <div class="col-sm-4">
                          <!-- text input -->
                          <div class="form-group">
                            <?php if (!empty($_SESSION['gallery_data']['album_image'])) : ?>
                              <input type="text" name="album_video_title[]" class="form-control" value="<?= !empty($_SESSION['gallery_data']['album_image']) ? $_SESSION['gallery_data']['album_image'] : ""  ?>" placeholder="Enter Video Title..." />
                            <?php else : ?>
                              <input type="text" name="album_video_title[]" class="form-control" placeholder="Enter Video Title..." />
                            <?php endif; ?>

                          </div>
                        </div>

                        <div class="col-sm-6">
                          <div class="form-group">
                            <?php if (!empty($_SESSION['gallery_data']['album_image'])) : ?>
                              <input type="text" name="album_video_code[]" class="album_video1 form-control" value="<?= !empty($_SESSION['gallery_data']['album_image']) ? $_SESSION['gallery_data']['album_image'] : ""  ?>" placeholder="Enter Video Title..." />
                            <?php else : ?>
                              <input type="text" class="album_video1 album_video_code form-control" name="album_video_code[]" placeholder="Enter Video code..." />
                            <?php endif; ?>
                          </div>
                        </div>
                        <div class="col-sm-1">

                        </div>
                        <div class="col-sm-1">
                          <button class="btn btn-danger delete-video-field ms-3">
                            <i class="fa-solid fa-trash"></i>
                          </button>
                        </div>
                      </div>

                    </div>


                  </div>
                  <div class="col-sm-12 mt-2">
                    <div role="button" id="addVideoButton" class="col-sm-12 border text-muted text-center rounded-pill">
                      Add New Line
                    </div>

                  </div>
                </div>



                <div class="row">
                  <div class="col-sm-12">
                    <!-- radio -->
                    <label class="form-check-label">Select Status</label>
                    <div class="form-group  ">
                      <?php if (!empty($_SESSION['gallery_data'][0])) : ?>

                        <div class="form-check mr-5">
                          <input class="form-check-input" type="radio" name="status" required value="1" <?= $_SESSION['gallery_data'][0]['status'] === "1" ? "checked" : ""    ?>>
                          <label class=" form-check-label">Active</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="status" required value="0" <?= $_SESSION['gallery_data'][0]['status'] === "0" ? "checked" : ""    ?>>
                          <label class="form-check-label">Block</label>
                        </div>


                      <?php else : ?>
                        <div class="form-check mr-5">
                          <input class="form-check-input x" type="radio" name="status" required value="1" checked>
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
                <button type="submit" class="btn btn-primary mt-3" name="add_gallery_image">
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
  var albumsData = <?php echo $json_albums ?>

  $(document).ready(function() {

    // Show uploadImagesContainer by default
    $('#uploadImagesContainer').show();
    $('#uploadImagesContainer input[type="file"]').prop('required', true);
    $('#uploadVideosContainer input[type="text"]').prop('required', false);
    $('#uploadVideosContainer input').val("");;

    $('#uploadVideosContainer').hide();

    // Event listener for multimedia type radio buttons
    $('input[type=radio][name=type]').change(function() {
      if (this.value == '1') {
        $('#uploadImagesContainer').show();
        $('#uploadVideosContainer').hide();
        // Set required attribute for image upload fields
        $('#uploadVideosContainer input[type="text"]').val("");;
        $('#uploadImagesContainer input[type="file"]').prop('required', true);
        $('#uploadVideosContainer .album_video_code').prop('required', false);

      } else if (this.value == '0') {
        $('#uploadImagesContainer').hide();
        $('#uploadVideosContainer').show();
        $('#uploadImagesContainer input[type="text"]').val("");;
        $('#uploadVideosContainer .album_video_code').prop('required', true);
        $('#uploadImagesContainer input[type="file"]').prop('required', false);
      }
    });


    // Event listener for the year select element
    $("#year_id").change(function() {
      // Get the selected year_id
      var year_id = $(this).val();

      // Filter albums based on the selected year_id
      var filteredAlbums = albumsData.filter((album) => album.year_id == year_id);

      // // Log the filtered albums to the console
      // console.log(filteredAlbums);

      // Clear existing options in the album_id select element
      $("#album_id").empty();

      // Add new options based on the filtered albums
      $("#album_id").append('<option value=""></option>');
      $.each(filteredAlbums, function(index, album) {
        $("#album_id").append('<option value="' + album.id + '">' + album.name + '</option>');
      });
    });







    // Add Image button click event
    $("#addImageButton").click(function() {
      var clonedField = $(".image-upload-field").first().clone();
      $(".imageUploadFields").append(clonedField);
      clonedField.find("input[type='text']").val("");
      clonedField.find("input[type='file']").val("");
      clonedField.find(".custom-file-label").text("Choose image"); // Reset the label text
      clonedField.find(".galleryImagePreviewContainer").empty(); // Clear the image preview
      clonedField.find(".delete-image-field").removeClass("d-none");
    });

    // Delete Image button click event
    $(document).on("click", ".delete-image-field", function() {
      if ($(".image-upload-field").length > 1) {
        $(this).closest(".image-upload-field").remove();
      }
    });

    // Add event listener for image input change
    $(document).on('change', '.album_image1', function(event) {
      const fileInput = $(this);
      const file = $(this)[0].files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const imagePreviewContainer = fileInput.closest('.image-upload-field').find(
            '.galleryImagePreviewContainer');
          const imgElement = $('<img>').attr('src', e.target.result)
            .addClass('img-fluid')
            .css({
              width: '38px',
              height: '38px',
              objectFit: 'cover'
            });
          imagePreviewContainer.empty(); // Clear previous image previews
          imagePreviewContainer.append(imgElement);
        };
        reader.readAsDataURL(file);

        // Update the label text with the filename
        $(this).siblings('.custom-file-label').text(file.name);
      }
    });






    //  for Video clonedField

    // Add Image button click event
    $("#addVideoButton").click(function() {
      var clonedField = $(".video-upload-field").first().clone();
      $(".videoUploadFields").append(clonedField);
      clonedField.find("input[type='text']").val("");
      clonedField.find("input[type='file']").val("");
      clonedField.find(".delete-video-field").removeClass("d-none");
    });

    // Delete Image button click event
    $(document).on("click", ".delete-video-field", function() {
      if ($(".video-upload-field").length > 1) {
        $(this).closest(".video-upload-field").remove();
      }
    });
  })
</script>