<?php


$currentPath = $_SERVER['PHP_SELF'];
$pathArr = explode("/", $currentPath);
$directoryName = $pathArr[count($pathArr) - 2];
$fileName = $pathArr[count($pathArr) - 1];
$file = $directoryName . "/" . $fileName;


?>



<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link">
    <img src="../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
      style="opacity: .8">
    <span class="brand-text font-weight-light">MWS</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">

          <?php if (isset($_SESSION['user'])) : ?>

          <?= $_SESSION['user']['name'] ?>
          <?php endif; ?>
        </a>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <li class="nav-item <?= $fileName == "dashboard.php"   ? "menu-open" : "" ?>"">
            <a href=" #" class="nav-link <?= $fileName == "dashboard.php"   ? "active" : "" ?>">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            Dashboard
            <i class="right fas fa-angle-left"></i>
          </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../dashboard/dashboard.php"
                class="nav-link  <?= $fileName == "dashboard.php"   ? "active" : "" ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Dashboard</p>
              </a>
            </li>

          </ul>
        </li>
        <li
          class="nav-item <?= ($file == "financial-year/listing.php" || $file == "financial-year/edit.php") ? "menu-open" : "" ?>">
          <a href="#"
            class="nav-link    <?= ($file == "financial-year/listing.php" || $file == "financial-year/edit.php") ? "active" : "" ?>">
            <i class="nav-icon fas fa-copy"></i>
            <p>
              Financial Year
              <i class="fas fa-angle-left right"></i>
              <span class="badge badge-info right">6</span>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../financial-year/listing.php"
                class="nav-link <?= ($file == "financial-year/listing.php" || $file == "financial-year/edit.php") ? "active" : "" ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Financial Year</p>
              </a>
            </li>

          </ul>
        </li>

        <li
          class="nav-item <?= ($file == "album/listing.php" || $file == "album/show.php" || $file == "gallery/listing.php" || $file == "album/edit.php" || $file == "gallery/add.php" || $file == "gallery/edit.php")  ? "menu-open" : "" ?>">
          <a href="#"
            class="nav-link <?= ($file == "album/listing.php" || $file == "album/show.php" || $file == "gallery/listing.php" || $file == "album/edit.php" || $file == "gallery/add.php" || $file == "gallery/edit.php")   ? "active" : "" ?>">
            <i class="nav-icon fas fa-tree"></i>
            <p>
              Album
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview ">
            <li class="nav-item">
              <a href="../album/listing.php?fyID=all"
                class="nav-link <?= ($file == "album/listing.php" || $file == "album/show.php" || $file == "album/edit.php")   ? "active" : "" ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Album</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../gallery/listing.php"
                class="nav-link <?= ($file == "gallery/listing.php"  || $file == "gallery/add.php" || $file == "gallery/edit.php")    ? "active" : "" ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Album Gallery</p>
              </a>
            </li>

          </ul>
        </li>


      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>