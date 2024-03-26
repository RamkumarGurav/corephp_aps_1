<?php

$base_url = "http://localhost/xampp/MARS/appolopublicschool.com/";

session_start();
if (!isset ($_SESSION["user"])) {
  header("Location: {$base_url}admin");
  exit();
}


require "../controller/FinancialYear.php";
require "../controller/Album.php";
require "../controller/Gallery.php";
require "../controller/Login.php";



include ("../inc/header.php");



include ("../inc/leftnav.php");

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $base_url; ?>>Home</a></li>
            <li class=" breadcrumb-item active">Dashboard</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row"></div>

      <!-- /.row -->
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner p-3">
              <h3>
                <?= $years_count; ?>
              </h3>

              <p class="">Total Financial Years</p>

            </div>

            <div class="icon">
              <!-- <i class="ion ion-person-add"></i> -->
            </div>
            <a href="../financial-year/listing.php" class="small-box-footer">More info <i
                class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner p-3">
              <h3>
                <?= $numOfAlbums; ?>
              </h3>

              <p>Total Albums</p>
            </div>
            <div class="icon">
              <!-- <i class="ion ion-bag"></i> -->
              <!-- <i class="fa-solid fa-image"></i> -->
              <!-- <i class="ion images-outline"></i> -->
              <!-- <ion-icon name="images-outline"></ion-icon> -->
            </div>
            <a href="../album/listing.php" class="small-box-footer">More info <i
                class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner p-3">
              <h3>
                <?= $num_of_album_images ?>
              </h3>

              <p>Total Images/Videos</p>
            </div>
            <div class="icon">
              <!-- <i class="ion ion-stats-bars"></i> -->
            </div>
            <a href="../gallery/listing.php" class="small-box-footer">More info <i
                class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->


        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->

      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>




<?php

include ("../inc/footer.php")

  ?>