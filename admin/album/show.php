<?php
$base_url = "http://localhost/xampp/MARS/appolopublicschool.com/";

session_start();
$_SESSION['album_data'] = [];
$_SESSION['gallery_data'] = [];
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
        <div class="col-sm-6">
          <h1><?= $albumData['name'] ?></h1>
        </div>
        <div class="col-sm-6">
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
        <div class="Card " style="width:100%;">


          <div class="card-body" style="width:100%;">
            <div class="d-flex justify-content-end mb-3">
              <a href="<?= "../album/edit.php?albumID=" . $albumId ?>" class="btn btn-primary  mr-2">Edit Album</a>

            </div>
            <?php if (!empty($albumData)) : ?>
            <table class="table bg-white table-borderless" style="width:100%;">
              <thead class="bg-primary">
                <tr>
                  <th>Sl. No.</th>
                  <th>Properties</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
                <tr>

                  <td style="width:5%;">1</td>
                  <td style="width:20%;">ID</td>
                  <td style="width:40%;"><?= $albumData['id'] ?></td>

                </tr>
                <tr>

                  <td>2</td>
                  <td>Fiscal Year</td>
                  <td><?= $yearData['fiscal_year'] ?></td>

                </tr>
                <tr>

                  <td>3</td>
                  <td>Album Name</td>
                  <td><?= $albumData['name'] ?></td>

                </tr>
                <tr>

                  <td>4</td>
                  <td>Cover Image</td>
                  <td>
                    <?php if (!empty($albumData['cover_image'])) : ?>
                    <img
                      src="<?php echo "../../uploads/album/{$yearData['fiscal_year']}/cover_images/{$albumData['cover_image']}"; ?>"
                      alt="" style="height: 80px; width: 120px; object-fit: cover;">
                    <?php else : ?>
                    Image Not Found
                    <?php endif; ?>
                  </td>

                </tr>
                <tr>

                  <td>5</td>
                  <td>status</td>
                  <td><?php echo $albumData['status'] == 1 ? 'Active' : 'Blocked'; ?></td>

                </tr>
                <tr>

                  <td>6</td>
                  <td>Added on</td>
                  <td><?= $albumData['added_on'] ?></td>

                </tr>
                <tr>

                  <td>7</td>
                  <td>Updated on</td>
                  <td><?= $albumData['updated_on'] ?></td>

                </tr>

              </tbody>
              <tfoot>
                <tr>
                  <!-- <th>Sl. No.</th>
                  <th>Properties</th>
                  <th>Details</th> -->
                </tr>
              </tfoot>
            </table>
            <?php else : ?>
            <h4>No album Found </h4>
            <?php endif; ?>


          </div>
        </div>
      </div>
      <div class="row">


        <div class="col-sm-12" id="show_AlbumImages">
          <!-- general form elements disabled -->
          <div class="card card-warning">
            <div class="card-header">
              <h3 class="card-title text-uppercase"> Album Images/Videos</h3>
            </div>

            <div class="px-4 pt-2 d-flex justify-content-between align-items-center gap-2" style="width:100%;">
              <!--  -->
              <div class=" d-flex justify-content-end align-items-center" style="width:100%;">
                <a href="<?= "../gallery/add.php?albumID={$albumData['id']}" ?>" type="button"
                  class="btn btn-primary btn-sm">+Add More Images/Videos</a>
                <a href="<?= "../gallery/listing.php?#album{$albumData['id']}" ?>" class="btn btn-dark btn-sm mx-2">view
                  in
                  gallery</a>
                <button type="button" class="btn btn-success btn-sm mx-2"
                  onclick="activateSelectedAlbumImages()">Active</button>
                <button type="button" class="btn btn-danger btn-sm" onclick="blockselectedAlbumImages()">Block</button>
                <button type="button" class="btn btn-danger btn-sm mx-2" style="background-color:#FD7E14;"
                  onclick="deleteSelectedAlbumImages()">Delete</button>
              </div>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <?php if (!empty($albumImagesByAlbumId)) : ?>
              <table id="sortable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="position-relative"># <input type="checkbox"
                        class="all-album-images-checkbox position-absolute" style="top:50%;left:50%;"
                        onchange="selectAllAlbumImages(this)"></th>
                    <th class="align-middle text-center">Sl. No.</th>
                    <!-- <th>Album Name</th> -->
                    <th class="align-middle text-center">Image/Video Code</th>
                    <th class="align-middle text-center">Image/Video Title</th>
                    <th class="align-middle text-center">Reorder Action</th>
                    <!-- <th>Image ID</th> -->
                    <!-- <th>Image order</th> -->
                    <!-- <th>Financial Year</th> -->
                    <th class="align-middle text-center">Status</th>
                    <th class="align-middle text-center">Edit</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($albumImagesByAlbumId as $index => $albumImage) : ?>
                  <tr id="<?= $albumImage['id'] ?>" data-sort-id="<?= $albumImage['id'] ?>">
                    <td class="position-relative px-4"><input type="checkbox"
                        class="album-image-checkbox position-absolute" style="top:50%;left:50%;"
                        value="<?php echo $albumImage['id']; ?>"></td>
                    <td class="align-middle text-center"><?php echo $index + 1; ?></td>
                    <!-- <td align="center"><?php echo $albumData['name'] ?></a> -->

                    <td class="align-middle text-center">
                      <?php if ($albumImage['type'] == '1') : ?>
                      <?php if (!empty($albumImage['album_image'])) : ?>
                      <img
                        src="<?php echo "../../uploads/album/{$yearData['fiscal_year']}/album_images/{$albumImage['album_image']}"; ?>"
                        alt="" style="height: 58px; width: 71px; object-fit: cover;">
                      <?php else : ?>
                      Image Not Found
                      <?php endif; ?>
                      <?php else : ?>
                      <?php if (!empty($albumImage['album_image'])) : ?>
                      <?= $albumImage['album_image'] ?>
                      <?php else : ?>
                      Video Code Not Found
                      <?php endif; ?>
                      <?php endif; ?>

                    </td>
                    <td align="center" class="align-middle text-center">
                      <?php if (!empty($albumImage['name'])) : ?>
                      <p><?= $albumImage['name'] ?></p>
                      <?php else : ?>
                      NO_TITLE
                      <?php endif; ?>

                    </td>
                    <td class="align-middle text-center">
                      <a href="javascript:;" class="sort  "><i class="fa fa-fw fa-arrows-alt "></i></a>

                    </td>

                    <!-- <td class="d-flex justify-content-center align-items-center bg-warning">
                          <a href="javascript:;" class="sort"><i class="fa fa-fw fa-arrows-alt "></i></a>
                        </td> -->

                    <td class="align-middle text-center">
                      <?php echo $albumImage['status'] == 1 ? 
                          '<div class="badge badge-sm bg-success">Active</div>' : '<div class="badge badge-sm bg-danger">blocked</div>'; ?>
                    </td>
                    <td align="center" class="align-middle text-center"> <a
                        href="<?= "../gallery/edit.php?imageID={$albumImage['id']}" ?>" type="button"
                        class="btn btn-warning btn-sm">edit</a>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th class="align-middle text-center">#</th>
                    <th class="align-middle text-center">Sl. No.</th>
                    <!-- <th>Album Name</th> -->
                    <th class="align-middle text-center">Image/Video Code</th>
                    <th class="align-middle text-center">Image/Video Title</th>
                    <th class="align-middle text-center">Reorder Action</th>
                    <!-- <th>Image ID</th> -->
                    <!-- <th>Image order</th> -->
                    <!-- <th>Financial Year</th> -->
                    <th class="align-middle text-center">Status</th>
                    <th class="align-middle text-center">Edit</th>
                  </tr>
                </tfoot>
              </table>
              <?php else : ?>
              <h4>No Image/Videos Found In This Album </h4>
              <?php endif; ?>



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



<!-- //{--------------FOR REORDING OF TABLE-------------- -->
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"
  integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<script>
var toastElementFE = $("#customToastFE");
// Add "hide" class to the toast after 2 seconds
$(document).ready(function(e) {

  const urlParams = new URLSearchParams(window.location.search);
  const albumID = urlParams.get('albumID');
  // console.log(albumID);
  // Initialize jQuery UI sortable for table rows
  $('#sortable tbody').sortable({
    handle: 'a.sort', // Define the handle for dragging
    placeholder: "ui-state-highlight", // Define the placeholder style
    // Update function triggered after sorting
    update: function() {
      // Get the sorted order of rows and convert to comma-separated string
      var order = $('#sortable tbody').sortable('toArray', {
        attribute: 'data-sort-id'
      });
      sortOrder = order.join(',');
      // console.log(sortOrder);

      // Send AJAX POST request to update sorted rows
      $.post(
        '../controller/Gallery.php', {
          'action': 'updateSortedRows',
          'sortOrder': sortOrder,
          "album_id": albumID
        },
        // Callback function after AJAX request completes
        function(data, status) {

          if (status == "success") {
            console.log(status);
            // toastElementFE.removeClass("d-none");
            setTimeout(function() {
              toastElementFE.removeClass("d-none");
              toastElementFE.addClass(" alert-success");
              $("#customToastFEClose").after("successfully reordered");
            }, 300);
            setTimeout(function() {
              $('#customToastFEClose').alert("close");
              location.reload();
            }, 2500);

          } else {
            setTimeout(function() {
              toastElementFE.removeClass("d-none");
              toastElementFE.addClass(" alert-danger");
              $("#customToastFEClose").after("Failed to reordered");
            }, 300);
            setTimeout(function() {
              $('#customToastFEClose').alert("close");
              location.reload();
            }, 2500);
          }

        }
      );
    }



  });
  // Disable text selection while sorting
  $("#sortable").disableSelection();



  //--------------------------------------------------}
})
</script>
<!-- //--------------------------------------------------} -->
<script>
// --------------------------------------------------}
var toastElementFE = $("#customToastFE");
var numOfAlbumImages = "<?php echo $numOfAlbumImages;  ?>";

function getSelectedAlbumImages() {
  const selectedAlbumImages = [];
  document.querySelectorAll('.album-image-checkbox:checked').forEach(checkbox => {
    selectedAlbumImages.push(checkbox.value);
  });
  return selectedAlbumImages;
}

// Function to select or deselect all album checkboxes based on the state of the checkbox for selecting all albums
function selectAllAlbumImages(checkbox) {
  // Select all album checkboxes
  const allAlbumImagesCheckbox = document.querySelectorAll('.album-image-checkbox');

  // Iterate over each album checkbox
  allAlbumImagesCheckbox.forEach(album_image_checkbox => {
    // Set the checked state of each album checkbox to be the same as the state of the checkbox for selecting all albums
    album_image_checkbox.checked = checkbox.checked;
  });
}


function activateSelectedAlbumImages() {
  const selectedAlbumImages = getSelectedAlbumImages();

  if (selectedAlbumImages.length > 0) {
    // Specify the correct URL for activating albums
    $.post("../controller/Gallery.php?gallery_action=activate", { // Assuming controller/Album.php is the correct path
        ids: selectedAlbumImages
      },
      function(data, status) {
        if (status == "success") {
          console.log(status);
          // toastElementFE.removeClass("d-none");
          setTimeout(function() {
            toastElementFE.removeClass("d-none");
            toastElementFE.addClass(" alert-success");
            $("#customToastFEClose").after("successfully Activated");
          }, 300);
          setTimeout(function() {
            $('#customToastFEClose').alert("close");
            location.reload();
          }, 2500);

        } else {
          setTimeout(function() {
            toastElementFE.removeClass("d-none");
            toastElementFE.addClass(" alert-danger");
            $("#customToastFEClose").after("Failed to Activate");
          }, 300);
          setTimeout(function() {
            $('#customToastFEClose').alert("close");
            location.reload();
          }, 2500);
        }
      });
  } else {
    alert('Please select at least one album image to activate.');
  }
}

function blockselectedAlbumImages() {
  const selectedAlbumImages = getSelectedAlbumImages();
  if (selectedAlbumImages.length > 0) {
    // Specify the correct URL for blocking albums
    $.post("../controller/Gallery.php?gallery_action=block", { // Assuming controller/Album.php is the correct path
        ids: selectedAlbumImages
      },
      function(data, status) {
        if (status == "success") {
          console.log(status);
          // toastElementFE.removeClass("d-none");
          setTimeout(function() {
            toastElementFE.removeClass("d-none");
            toastElementFE.addClass(" alert-success");
            $("#customToastFEClose").after("successfully Blocked");
          }, 300);
          setTimeout(function() {
            $('#customToastFEClose').alert("close");
            location.reload();
          }, 2500);

        } else {
          setTimeout(function() {
            toastElementFE.removeClass("d-none");
            toastElementFE.addClass(" alert-danger");
            $("#customToastFEClose").after("Failed to block");
          }, 300);
          setTimeout(function() {
            $('#customToastFEClose').alert("close");
            location.reload();
          }, 2500);
        }
      });
  } else {
    alert('Please select at least one album image to block.');
  }
}


function deleteSelectedAlbumImages() {
  const selectedAlbumImages = getSelectedAlbumImages();

  // const selctedAlbumsArr = selectedAlbums.split(",");
  if (selectedAlbumImages.length > 0) {
    if (confirm("Are you sure you want to delete selected album images?")) {

      if (selectedAlbumImages.length == numOfAlbumImages) {
        const proceed = prompt('Type "confirm delete all", To proceed with deleting all selected album images:');
        if (proceed === "confirm delete all") {
          // User confirmed deletion, proceed with the deletion
          $.post("../controller/Gallery.php?gallery_action=delete", {
              ids: selectedAlbumImages
            },
            function(data, status) {
              if (status == "success") {
                console.log(status);
                // toastElementFE.removeClass("d-none");
                setTimeout(function() {
                  toastElementFE.removeClass("d-none");
                  toastElementFE.addClass(" alert-success");
                  $("#customToastFEClose").after("successfully Deleted");
                }, 300);
                setTimeout(function() {
                  $('#customToastFEClose').alert("close");
                  location.reload();
                }, 2500);

              } else {
                setTimeout(function() {
                  toastElementFE.removeClass("d-none");
                  toastElementFE.addClass(" alert-danger");
                  $("#customToastFEClose").after("Failed to Delete");
                }, 300);
                setTimeout(function() {
                  $('#customToastFEClose').alert("close");
                  location.reload();
                }, 2500);
              }
            });
        } else {

          alert("Deletion canceled. You Didn't type correctly Try Again.");
          // // Deselect the "Select All" checkbox

          location.reload();

        }

      } else {

        // Prompt user for secondary confirmation
        const proceed = prompt('Type "confirm delete", To proceed with deleting the selected album images:');
        if (proceed === "confirm delete") {
          // User confirmed deletion, proceed with the deletion
          $.post("../controller/Gallery.php?gallery_action=delete", {
              ids: selectedAlbumImages
            },
            function(data, status) {
              if (status == "success") {
                console.log(status);
                // toastElementFE.removeClass("d-none");
                setTimeout(function() {
                  toastElementFE.removeClass("d-none");
                  toastElementFE.addClass(" alert-success");
                  $("#customToastFEClose").after("successfully Deleted");
                }, 300);
                setTimeout(function() {
                  $('#customToastFEClose').alert("close");
                  location.reload();
                }, 2500);

              } else {
                setTimeout(function() {
                  toastElementFE.removeClass("d-none");
                  toastElementFE.addClass(" alert-danger");
                  $("#customToastFEClose").after("Failed to Delete");
                }, 300);
                setTimeout(function() {
                  $('#customToastFEClose').alert("close");
                  location.reload();
                }, 2500);
              }
            });
        } else {
          alert("Deletion canceled. You Didn't type correctly Try Again.");
          // // Deselect the "Select All" checkbox
          location.reload();
        }

      }

    } else {
      location.reload();
    }
  } else {
    alert('Please select at least one album Image to delete.');
  }
}
</script>