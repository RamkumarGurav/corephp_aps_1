<?php

$base_url = "http://localhost/xampp/MARS/appolopublicschool.com/";
session_start();
$_SESSION['album_data'] = [];
$_SESSION['gallery_data'] = [];
if (!isset($_SESSION["user"])) {
  header("Location: {$base_url}admin");
  exit();
}

include "../controller/Login.php";
include "../controller/FinancialYear.php";

$years_json = json_encode($years);

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
          <h1>Financial Year</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Financial Year</li>
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
          <div class="card">
            <div class="card-header d-flex justify-content-end align-items-center gap-2">
              <a href="../financial-year/edit.php" type="button" class="btn btn-primary btn-sm">Add</a>
              <button type="button" class="btn btn-success btn-sm mx-2"
                onclick="activateSelectedYears()">Active</button>


              <button type="button" class="btn btn-danger btn-sm" onclick="blockSelectedYears()">Block</button>
              <button type="button" class="btn btn-danger btn-sm mx-2" style="background-color:#FD7E14;"
                onclick="deleteSelectedYears()">Delete</button>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="position-relative"># <input type="checkbox" class="all-years-checkbox position-absolute"
                        style="top:50%;left:50%;" onchange="selectAllYears(this)"></th>
                    <th class="align-middle text-center">Sl. No.</th>
                    <th class="align-middle text-center">Start Year</th>
                    <th class="align-middle text-center">End Year</th>
                    <th class="align-middle text-center">Financial Year</th>
                    <th class="align-middle text-center">Status</th>
                  </tr>

                </thead>
                <tbody>
                  <?php foreach ($years as $index => $year) : ?>
                  <tr>
                    <td class="position-relative px-4"><input type="checkbox" class="year-checkbox position-absolute"
                        style="top:50%;left:50%;" value="<?php echo $year['id']; ?>"></td>
                    <td class="align-middle text-center"><a href="<?=
                                    "
                        http://localhost/xampp/MARS/appolopublicschool.com/admin/financial-year/edit.php?id={$year['id']}"
                                    ?>"><?php echo $index + 1; ?></a></td>
                    <td class="align-middle text-center"><?php echo $year['start_year']; ?></td>
                    <td class="align-middle text-center"><?php echo $year['end_year']; ?></td>
                    <td class="align-middle text-center"><?php echo $year['fiscal_year']; ?></td>
                    <td class="align-middle text-center">
                      <?php echo $year['status'] == 1 ?
                          '<div class="badge badge-sm bg-success">Active</div>' : '<div class="badge badge-sm bg-danger">blocked</div>'; ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th class="align-middle text-center">Sl. No.</th>
                    <th class="align-middle text-center">Start Year</th>
                    <th class="align-middle text-center">End Year</th>
                    <th class="align-middle text-center">Financial Year</th>
                    <th class="align-middle text-center">Status</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include("../inc/footer.php"); ?>

<script>
var toastElementFE = $("#customToastFE");
var numOfYears = "<?php echo $years_count;  ?>";

function getSelectedYears() {
  const selectedYears = [];
  document.querySelectorAll('.year-checkbox:checked').forEach(checkbox => {
    selectedYears.push(checkbox.value);
  })
  return selectedYears;
}

// Function to select or deselect all year checkboxes based on the state of the checkbox for selecting all years
function selectAllYears(checkbox) {
  // Select all year checkboxes
  const allYearsCheckbox = document.querySelectorAll('.year-checkbox');

  // Iterate over each year checkbox
  allYearsCheckbox.forEach(cb => {
    // Set the checked state of each year checkbox to be the same as the state of the checkbox for selecting all years
    cb.checked = checkbox.checked;
  });
}


function activateSelectedYears() {
  const selectedYears = getSelectedYears();
  if (selectedYears.length > 0) {
    $.post("../controller/FinancialYear.php?fy_action=activate", {
        ids: selectedYears
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
    alert('Please select at least one year to activate.');
  }
}

function blockSelectedYears() {
  const selectedYears = getSelectedYears();
  if (selectedYears.length > 0) {
    $.post("../controller/FinancialYear.php?fy_action=block", {
        ids: selectedYears
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
    alert('Please select at least one year to block.');
  }
}

function deleteSelectedYears() {
  const selectedYears = getSelectedYears();

  // const selctedYearsArr = selectedYears.split(",");
  if (selectedYears.length > 0) {


    if (confirm("Are you sure you want to delete selected Financial Years?")) {



      if (selectedYears.length == numOfYears) {
        const proceed = prompt('Type "confirm delete all", To proceed with deleting all selected Financial Years:');
        if (proceed === "confirm delete all") {
          // User confirmed deletion, proceed with the deletion
          $.post("../controller/FinancialYear.php?fy_action=delete", {
              ids: selectedYears
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
          // User did not type 'PROCEED TO DELETE ALL', so cancel deletion
          document.querySelectorAll('.year-checkbox:checked').forEach(checkbox => {
            checkbox.checked = false;
          });
          // Deselect the "Select All" checkbox
          document.querySelector('.all-years-checkbox').checked = false;
          alert("Deletion canceled. You Didn't type correctly Try Again.");

        }
      } else {


        // Prompt user for secondary confirmation

        const proceed = prompt('Type "confirm delete", To proceed with deleting the selected Years:');
        if (proceed === "confirm delete") {
          // User confirmed deletion, proceed with the deletion
          $.post("../controller/FinancialYear.php?fy_action=delete", {
              ids: selectedYears
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
    alert('Please select at least one Financial Year to delete.');
  }
}
</script>