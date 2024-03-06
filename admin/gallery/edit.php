<?php
session_start();
if (!isset($_SESSION["user"])) {
  header("Location: http://localhost/xampp/MARS/appolopublicschool.com/admin");
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
              <h3 class="card-title"> Added Gallery Image</h3>
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
                      <select class="form-control" id="year_id" name="year_id" required readonly>
                        <option value="<?= $yearDataInGalleryPage['id'] ?>"><?= $yearDataInGalleryPage['fiscal_year'] ?>
                        </option>
                        <!-- <?php foreach ($years as $year) : ?>
                        <option value="<?= $year['id']; ?>"
                          <?= $year["id"] == $albumDataInGalleryPage['year_id'] ? "selected" : ""; ?>>
                          <?= $year['fiscal_year']; ?></option>
                        <?php endforeach; ?> -->

                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <!-- select -->
                    <div class="form-group">
                      <label>Select Album</label>
                      <select class="form-control" id="album_id" name="album_id" required readonly>
                        <option value="<?= $albumDataInGalleryPage['id'] ?>"><?= $albumDataInGalleryPage['name'] ?>
                        </option>
                        <!-- <?php foreach ($albums as $album) : ?>

                        <option value="<?= $album["id"] ?>"
                          <?= $galleryImageData["album_id"] == $album['id'] ? "selected" : ""; ?>>
                          <?= $album["name"] ?>
                        </option>
                        <?php endforeach; ?> -->

                      </select>
                    </div>
                  </div>
                </div>


                <?php if ($galleryImageData['type'] == '1') : ?>
                  <div class="row">
                    <div class="col-sm-12">
                      <!-- text input -->
                      <div class="form-group ">
                        <label for="album_image">Upload Image</label>
                        <div class="d-flex  align-items-center ">
                          <div class=" d-flex justify-content-between align-items-center mr-4">
                            <?php if (!empty($_SESSION['gallery_data']['name'])) : ?>
                              <input type="text" name="name" id="name" class="form-control" value="<?= !empty($_SESSION['gallery_data']['name']) ? $_SESSION['gallery_data']['name'] : ""  ?>" placeholder="Enter Image Name..." />
                            <?php else : ?>
                              <input type="text" name="name" id="name" class="form-control" value="<?= $galleryImageData != null ? $galleryImageData["name"] : ""  ?>" placeholder="Enter Image Name..." />
                            <?php endif; ?>

                          </div>
                          <div class="custom-file mr-4" style="max-width: 500px">
                            <input type="file" class="custom-file-input" id="album_image" name="album_image" accept="image/png, image/jpeg,image/jpg" />
                            <label class="custom-file-label" for="exampleInputFile"><?= $galleryImageData["album_image"] ?? "Choose Image"  ?></label>
                          </div>
                          <div id="albumImagePreviewContainer" class="d-flex justify-content-center align-items-center" style="height: 38px; width: 41px">

                            <img src="<?= "../../uploads/album/" . $yearDataInGalleryPage["fiscal_year"] . "/album_images/" . $galleryImageData["album_image"] ?>" alt="<?= $galleryImageData["name"]  ?>" style="height: 38px; width: 41px;object-fit:cover;">

                          </div>

                        </div>
                        <small class="text-muted mt-1 mb-2"> &#40; only
                          PNG/JPEG/JPG image formats less than
                          2MB
                          are allowed &#41;</small>
                      </div>

                    </div>
                  </div>
                <?php else : ?>
                  <div class="row">
                    <div class="col-sm-12">
                      <!-- text input -->
                      <div class="form-group ">
                        <div class="row " style="width: 100%;">
                          <div class=" col-sm-6 ">
                            <label for="album_video_title">Video Title</label>
                            <input type="text" name="album_video_title" id="album_video_title" class="form-control" value="<?= !empty($galleryImageData['name']) ? $galleryImageData['name'] : ""  ?>" placeholder="Enter Video Title..." />


                          </div>
                          <div class=" col-sm-6 ">
                            <label for="album_video_code">Video Code</label>
                            <input type="text" name="album_video_code" id="album_video_code" class="form-control" value="<?= !empty($galleryImageData['album_image']) ? $galleryImageData['album_image'] : ""  ?>" placeholder="Enter Video Code..." />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>




                <!-- <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="">New Upload</label>
                      <div class="d-flex align-items-center">
                        <div class="custom-file mr-4" style="max-width: 500px">
                          <input type="file" class="custom-file-input" id="albumx_image" name="albumx_image"
                            accept="image/png, image/jpeg,image/jpg" />
                          <label class="custom-file-label" for="exampleInputFile">Choose image</label>
                        </div>
                        <div id="albumxImagePreviewContainer" class="d-flex justify-content-center align-items-center"
                          style="height: 38px; width: 41px">


                        </div>

                      </div>
                      <small class="text-muted mt-1 mb-2"> &#40; only
                        PNG/JPEG/JPG image formats less than
                        2MB
                        are allowed &#41;</small>
                    </div>
                  </div>
                </div> -->

                <div class="row">
                  <div class="col-sm-12">
                    <!-- radio -->
                    <label class="form-check-label">Select Status</label>
                    <div class="form-group  ">
                      <?php if ((!empty($_SESSION['gallery_data']))) : ?>
                        <div class="form-check mr-5">
                          <input class="form-check-input" type="radio" name="status" required value="1" <?= $_SESSION['gallery_data']['status'] === "1" ? "checked" : ""    ?>>
                          <label class=" form-check-label">Active</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="status" required value="0" <?= $_SESSION['gallery_data']['status'] === "0" ? "checked" : ""    ?>>
                          <label class="form-check-label">Block</label>
                        </div>

                      <?php else : ?>
                        <div class="form-check mr-5">
                          <input class="form-check-input" type="radio" name="status" required value="1" <?= ($galleryImageData != null && $galleryImageData["status"] == 1) ? "checked" : ""    ?>>
                          <label class=" form-check-label x">Active</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="status" required value="0" <?= ($galleryImageData != null && $galleryImageData["status"] == 0) ? "checked" : ""    ?>>
                          <label class="form-check-label">Block</label>
                        </div>
                      <?php endif; ?>


                    </div>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3" name="update_gallery_image">
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














  })
</script>
<script>
  $(document).ready(function() {
    // Add event listener for image input change
    $('#album_image').change(function(event) {
      // Get the selected file
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const imagePreviewContainer = $('#albumImagePreviewContainer');
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

    // Add event listener for image input change
    $('#albumx_image').change(function(event) {

      // Get the selected file
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const imagePreviewContainer = $('#albumxImagePreviewContainer');
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