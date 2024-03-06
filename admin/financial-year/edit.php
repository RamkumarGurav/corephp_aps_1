<?php
$base_url = "http://localhost/xampp/MARS/appolopublicschool.com/";

session_start();
if (!isset($_SESSION["user"])) {
  header("Location: {$base_url}admin");
  exit();
}


include("../controller/Login.php");
include("../controller/FinancialYear.php");


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
          <!-- <h1>Add Financial Year</h1> -->
        </div>
        <div class="col-sm-12">
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


        <div class="col-sm-12">
          <!-- general form elements disabled -->
          <div class="card card-warning">
            <div class="card-header">
              <h3 class="card-title"> <?= $yearData != null  ? "Added Financial Year" : "Add Financial Year"    ?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">





              <form action="" method="post">
                <div class="row">


                  <div class="col-sm-12">
                    <!-- text input -->
                    <div class="form-group">
                      <label>Start Year</label>
                      <input type="text" class="form-control" name="start_year" id="start_year" value="<?= $yearData != null ? $yearData["start_year"] : ""   ?>" required placeholder=" Enter Start Year...">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <!-- text input -->
                    <div class="form-group">
                      <label>End Year</label>
                      <input type="text" class="form-control" name="end_year" id="end_year" value="<?= $yearData != null ? $yearData["end_year"] : ""   ?>" required placeholder="Enter End Year...">
                    </div>
                  </div>
                </div>



                <div class="row">

                  <div class="col-sm-12">
                    <!-- text input -->
                    <div class="form-group">
                      <label>Fiscal Year</label>
                      <input type="text" class="form-control" name="fiscal_year" id="fiscal_year" value="<?= $yearData != null ? $yearData["fiscal_year"] : ""   ?>" required placeholder="Fiscal Year.." readonly>
                    </div>
                  </div>
                </div>



                <div class="row">

                  <div class="col-sm-12">
                    <!-- radio -->
                    <label class="form-check-label">Select Status</label>
                    <div class="form-group  ">
                      <div class="form-check mr-5">
                        <input class="form-check-input" type="radio" name="status" required value="1" <?= ($yearData != null && $yearData["status"] == 1) ? "checked" : "checked"    ?>>
                        <label class=" form-check-label">Active</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" required value="0" <?= ($yearData != null && $yearData["status"] == 0) ? "checked" : ""    ?>>
                        <label class="form-check-label">Block</label>
                      </div>

                    </div>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary" name="submit_year_btn">Submit</button>

              </form>
            </div>
            <!-- /.card-body -->
          </div>

        </div>
        <!--/.col (right) -->

      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- jQuery -->





<?php
include("../inc/footer.php")
?>

<script>
  $(function() {
    // Function to calculate fiscal year
    function calculateFiscalYear(startYear, endYear) {
      // Extracting last two digits of start and end years
      let startYearLastTwoDigits = startYear.toString().substr(0, 4);
      let endYearLastTwoDigits = endYear.toString().substr(-2);

      // Constructing fiscal year string
      let fiscalYear = startYearLastTwoDigits + '-' + endYearLastTwoDigits;

      // Returning fiscal year
      return fiscalYear;
    }

    // Function to update fiscal year field
    function updateFiscalYear() {
      // Getting start year and end year values
      let startYear = $('#start_year').val();
      let endYear = $('#end_year').val();

      // Checking if start year and end year are not empty
      if (startYear !== '' && endYear !== '') {
        // Calculating fiscal year
        let fiscalYear = calculateFiscalYear(startYear, endYear);

        // Setting fiscal year value to the input field
        $('#fiscal_year').val(fiscalYear);
      }
    }

    // Calling updateFiscalYear function on input change for start year and end year fields
    $('#start_year, #end_year').on('input', function() {
      updateFiscalYear();
    });

    // Calling updateFiscalYear function on page load
    updateFiscalYear();
  });
</script>