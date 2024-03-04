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




?>
<link rel="stylesheet" href="../plugins/ekko-lightbox/ekko-lightbox.css">
<?php include("../inc/leftnav.php"); ?>





<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Gallery</h1>
        </div>
        <div class="col-sm-6">
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
    <div class="d-flex justify-content-end mb-3">
      <a href="../gallery/add.php" class="btn btn-primary btn-sm mr-2">+Add Images</a>

    </div>
    <div class="container-fluid">

      <div class="row">

        <?php if (!empty($years)) : ?>
        <!-- Check if financial years array is not empty -->
        <?php foreach ($years as $year) : ?>
        <div class="col-12 ">
          <div class="d-flex justify-content-between p-2 rounded-top row">
            <p class="h3 "><u>Financial Year: <?= $year['fiscal_year'] ?></u></p>
          </div>
          <div class="p-4 row">
            <div class="col-12">
              <?php $filtered_albums = array_filter($albums, function ($item) use ($year) {
                    return $item["year_id"] == $year['id'];
                  }); ?>

              <?php if (empty($filtered_albums)) : ?>
              <!-- No albums found in this financial year -->
              <h2 class="text-center">No Albums found in this Financial year</h2>
              <?php else : ?>
              <!-- Albums found in this financial year -->
              <?php if (!empty($album_images)) : ?>
              <?php foreach ($filtered_albums as $filtered_album) : ?>

              <?php $filtered_album_images = array_filter($album_images, function ($item) use ($filtered_album) {
                          return $item["album_id"] == $filtered_album['id'];
                        }); ?>

              <div class="card card-primary">
                <div class="bg-primary d-flex justify-content-between p-2 " style="width:100%;">
                  <h4 class="  text-capitalize"><?= $filtered_album["name"] ?></h4>
                  <h6 class="">Total Images:


                    <?= $filtered_album_images != false ? count($filtered_album_images) : 0; ?></h6>
                </div>
                <div class="card-body">
                  <?php if (empty($filtered_album_images)) : ?>
                  <!-- Album is empty -->
                  <h2 class="text-center">This Album is Empty</h2>
                  <?php else : ?>
                  <!-- Album has images -->

                  <!-- Filter container -->
                  <div class="filter-container p-0 row">
                    <?php foreach ($filtered_album_images as $album_image) : ?>
                    <div class="filtr-item col-sm-3 mb-1" data-category="<?= "1 ,4" ?>" data-sort="white sample"
                      style="width:100%;height:180px;">
                      <a href="<?= "../../uploads/album/{$year['fiscal_year']}/album_images/{$album_image['album_image']}" ?>"
                        data-toggle="lightbox" data-title="<?= $album_image['name'] ?>" style="height:100%;width:100%;">
                        <img
                          src="<?= "../../uploads/album/{$year['fiscal_year']}/album_images/{$album_image['album_image']}" ?>"
                          class="img-fluid mb-2" alt="<?= $album_image['name'] ?>"
                          aria-label="<?= $album_image['name'] ?>" style="height:100%;width:100%;object-fit:cover;" />
                      </a>
                    </div>
                    <?php endforeach; ?>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php endforeach; ?>
              <?php endif; ?>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
        <?php else : ?>
        <!-- No financial years found -->
        <h2 class="text-center">No Albums Found.</h2>
        <?php endif; ?>






      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->






<?php include("../inc/footer.php"); ?>



<!-- Ekko Lightbox -->
<script src="../plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
<!-- Filterizr-->
<script src="../plugins/filterizr/jquery.filterizr.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- Page specific script -->
<script>
$(function() {
  $(document).on('click', '[data-toggle="lightbox"]', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox({
      alwaysShowClose: true
    });
  });

  $('.filter-container').filterizr({
    gutterPixels: 3
  });
  $('.btn[data-filter]').on('click', function() {
    $('.btn[data-filter]').removeClass('active');
    $(this).addClass('active');
  });
})
</script>