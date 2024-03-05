<?php

session_start();
$_SESSION['album_data'] = [];
$_SESSION['gallery_data'] = [];
if (!isset($_SESSION["user"])) {
  header("Location: http://localhost/xampp/MARS/appolopublicschool.com/admin");
  exit();
}




include "../controller/Login.php";
include("../controller/FinancialYear.php");
include("../controller/Album.php");






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
          <h1>Album</h1>
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
        <div class="col-12">
          <div class="card" style="width:100%;">

            <div class="px-4 pt-2 d-flex justify-content-between align-items-center gap-2" style="width:100%;">
              <!-- Add a select dropdown for fiscal year -->
              <div class="form-group justify-self-start">
                <label for="fiscal-year">Select Fiscal Year:</label>
                <select class="form-control" id="fiscal-year" onchange="filterAlbumsByYear(this)">
                  <option value="all">All</option>
                  <?php foreach ($years as $fy) : ?>
                  <option value="<?= $fy['id']  ?>" <?= $fyID == $fy['id'] ? "selected" : "" ?>>
                    <?php echo $fy['fiscal_year']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class=" d-flex justify-content-end align-items-center">
                <a href="../album/edit.php" type="button" class="btn btn-primary btn-sm">+Add New Album</a>
                <button type="button" class="btn btn-success btn-sm mx-2"
                  onclick="activateSelectedAlbums()">Active</button>
                <button type="button" class="btn btn-danger btn-sm" onclick="blockSelectedAlbums()">Block</button>
                <button type="button" class="btn btn-danger btn-sm mx-2" style="background-color:#FD7E14;"
                  onclick="deleteSelectedAlbums()">Delete</button>
              </div>

            </div>


            <?php if ($fyID == 'all' or empty($fyID)) : ?>
            <div class="card-body">

              <?php if (!empty($albums)) : ?>
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class=" position-relative"># <input type="checkbox"
                        class="all-albums-checkbox position-absolute" style="top:50%;left:50%;"
                        onchange="selectAllAlbums(this)"></th>
                    <th class="align-middle text-center">Sl. No.</th>
                    <th class="align-middle text-center">album name</th>
                    <th class="align-middle text-center">cover image</th>
                    <th class="align-middle text-center">financial year</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($albums as $index => $album) : ?>
                  <tr id="<?= $album['id'] ?>" data-sort-id="<?= $album['id'] ?>">
                    <td class="align-middle text-center position-relative px-4"><input type="checkbox"
                        class="album-checkbox position-absolute" style="top:50%;left:50%;"
                        value="<?php echo $album['id']; ?>"></td>
                    <td class="align-middle text-center"><?php echo $index + 1; ?></td>

                    <td class="align-middle text-left"><a href="<?=
                                                                      "http://localhost/xampp/MARS/appolopublicschool.com/admin/album/show.php?albumID={$album['id']}"
                                                                      ?>"><u><?php echo $album['name']; ?></u></a>
                    </td>
                    <td class="align-middle text-center">
                      <?php if (!empty($album['cover_image'])) : ?>
                      <img
                        src="<?php echo "../../uploads/album/{$album['fy_name']}/cover_images/{$album['cover_image']}"; ?>"
                        alt="" style="height: 38px; width: 41px; object-fit: cover;">
                      <?php else : ?>
                      Image Not Found
                      <?php endif; ?>
                    </td>

                    <td class="align-middle text-center"><?php echo $album['fy_name'] ?></td>
                    <td class="align-middle text-center"><?php echo $album['status'] == 1 ? 'Active' : 'Blocked'; ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th class="align-middle text-center">#</th>
                    <th class="align-middle text-center">Sl. No.</th>
                    <th class="align-middle text-center">album name</th>
                    <th class="align-middle text-center">cover image</th>
                    <th class="align-middle text-center">financial year</th>
                    <th class="align-middle text-center">Status</th>
                  </tr>
                </tfoot>
              </table>
              <?php else : ?>
              <h4>No albums Found </h4>
              <?php endif; ?>


            </div>
            <?php else : ?>
            <div class="card-body">

              <?php if (!empty($albums)) : ?>
              <table id="sortable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class=" position-relative"># <input type="checkbox"
                        class="all-album-images-checkbox position-absolute" style="top:50%;left:50%;"
                        onchange="selectAllAlbums(this)"></th>
                    <th class="align-middle text-center">Sl. No.</th>
                    <th class="align-middle text-center">album name</th>
                    <th class="align-middle text-center">cover image</th>
                    <th class="align-middle text-center">Reorder Action</th>
                    <!-- <th>album ID</th>

                    <th>album order</th> -->
                    <th class="align-middle text-center">financial year</th>
                    <th class="align-middle text-center">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($albums as $index => $album) : ?>
                  <tr id="<?= $album['id'] ?>" data-sort-id="<?= $album['id'] ?>">
                    <td class="align-middle text-center position-relative px-4"><input type="checkbox"
                        class="album-checkbox position-absolute" style="top:50%;left:50%;"
                        value="<?php echo $album['id']; ?>"></td>
                    <td class="align-middle text-center"><?php echo $index + 1; ?></td>

                    <td class="align-middle text-left"><a href="<?=
                                                                      "http://localhost/xampp/MARS/appolopublicschool.com/admin/album/show.php?albumID={$album['id']}"
                                                                      ?>"><u><?php echo $album['name']; ?></u></a></td>
                    <td class="align-middle text-center">
                      <?php if (!empty($album['cover_image'])) : ?>
                      <img
                        src="<?php echo "../../uploads/album/{$album['fy_name']}/cover_images/{$album['cover_image']}"; ?>"
                        alt="" style="height: 38px; width: 41px; object-fit: cover;">
                      <?php else : ?>
                      Image Not Found
                      <?php endif; ?>
                    </td>
                    <td class="align-middle text-center"><a href="javascript:;" class="sort"><i
                          class="fa fa-fw fa-arrows-alt "></i></a>
                      <!-- 
                    <td align="center"><?php echo $album['id'] ?></a>
                    <td align="center"><?php echo $album['album_order'] ?></a> -->
                    <td class="align-middle text-center"><?php echo $album['fy_name'] ?></td>
                    <td class="align-middle text-center"><?php echo $album['status'] == 1 ? 'Active' : 'Blocked'; ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th class="align-middle text-center">#</th>
                    <th class="align-middle text-center">Sl. No.</th>
                    <th class="align-middle text-center">album name</th>
                    <th class="align-middle text-center">cover image</th>
                    <th class="align-middle text-center">Reorder Action</th>
                    <!-- <th>album ID</th>

                    <th>album order</th> -->
                    <th class="align-middle text-center">financial year</th>
                    <th class="align-middle text-center">Status</th>
                  </tr>
                </tfoot>
              </table>
              <?php else : ?>
              <h4>No albums Found </h4>
              <?php endif; ?>


            </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>





<!-- Include jQuery and jQuery UI libraries -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script> -->
<!-- <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script> -->

<!-- JavaScript code for sorting functionality -->


<?php include("../inc/footer.php"); ?>





<!-- //{--------------FOR REORDING OF TABLE-------------- -->
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"
  integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<script>
var toastElementFE = $("#customToastFE");
// Add "hide" class to the toast after 2 seconds
$(document).ready(function(e) {

  const urlParams = new URLSearchParams(window.location.search);
  const fyID = urlParams.get('fyID');
  // console.log(fyID);
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

      // // Send AJAX POST request to update sorted rows
      $.post(
        '../controller/Album.php', {
          'action': 'updateSortedRows',
          'sortOrder': sortOrder,
          "year_id": fyID
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
var numOfAlbums = "<?php echo $numOfAlbums;  ?>";
// console.log(numOfAlbums);

function getSelectedAlbums() {
  const selectedAlbums = [];
  document.querySelectorAll('.album-checkbox:checked').forEach(checkbox => {
    selectedAlbums.push(checkbox.value);
  });
  return selectedAlbums;
}

// Function to select or deselect all album checkboxes based on the state of the checkbox for selecting all albums
function selectAllAlbums(checkbox) {
  // Select all album checkboxes
  const allAlbumsCheckbox = document.querySelectorAll('.album-checkbox');

  // Iterate over each album checkbox
  allAlbumsCheckbox.forEach(album_checkbox => {
    // Set the checked state of each album checkbox to be the same as the state of the checkbox for selecting all albums
    album_checkbox.checked = checkbox.checked;
  });
}


function activateSelectedAlbums() {
  const selectedAlbums = getSelectedAlbums();

  if (selectedAlbums.length > 0) {
    // Specify the correct URL for activating albums
    $.post(
      "../controller/Album.php?album_action=activate", { // Assuming controller/Album.php is the correct path
        ids: selectedAlbums
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
    alert('Please select at least one album to activate.');
  }
}

function blockSelectedAlbums() {
  const selectedAlbums = getSelectedAlbums();
  if (selectedAlbums.length > 0) {
    // Specify the correct URL for blocking albums
    $.post("../controller/Album.php?album_action=block", { // Assuming controller/Album.php is the correct path
        ids: selectedAlbums
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
            $("#customToastFEClose").after("Failed to Block");
          }, 300);
          setTimeout(function() {
            $('#customToastFEClose').alert("close");
            location.reload();
          }, 2500);
        }
      });
  } else {
    alert('Please select at least one album to block.');
  }
}





function deleteSelectedAlbums() {
  const selectedAlbums = getSelectedAlbums();

  if (selectedAlbums.length > 0) {

    if (selectedAlbums.length == numOfAlbums) {
      const proceed = prompt("type 'proceed to delete all', To proceed with deleting all selected albums:");
      if (proceed === "proceed to delete all") {
        // User confirmed deletion, proceed with the deletion
        $.post("../controller/Album.php?album_action=delete", {
            ids: selectedAlbums
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

      if (confirm("Are you sure you want to delete selected albums?")) {
        // Prompt user for secondary confirmation

        // User confirmed deletion, proceed with the deletion
        $.post("../controller/Album.php?album_action=delete", {
            ids: selectedAlbums
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
          }
        );

      } else {
        // Deselect the "Select All" checkbox

        location.reload();

      }
    }
  } else {
    alert('Please select at least one album  to delete.');
  }
}




function filterAlbumsByYear(select) {
  const yearId = select.value;
  const url = new URL(window.location.href);
  if (yearId) {
    url.searchParams.set('fyID', yearId);
  }

  window.location.href = url.toString();
}
$(document).ready(function() {
  // Check if the fyID query parameter exists in the URL
  const urlParams = new URLSearchParams(window.location.search);
  const fyID = urlParams.get('fyID');

  // If fyID exists, select the corresponding option in the select dropdown
  if (fyID) {
    $("#fiscal-year").val(fyID);
  }

  // Rest of your JavaScript code...
});
</script>