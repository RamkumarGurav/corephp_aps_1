<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MWS | Financial Year Edit</title>


  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">

  <!-- //{---------------------------- -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Dropdown
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#">Disabled</a>
        </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form>
    </div>
  </nav>
  <!-- //--------------------------------------------------} -->


  <div class="container">
    <h2>Bordered Table</h2>
    <p>The .table-bordered class adds borders on all sides of the table and the cells:</p>
    <table class="table table-bordered ">
      <thead>
        <tr>
          <th>Firstname</th>
          <th>Lastname</th>
          <th>Email</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>John</td>
          <td>Doe</td>
          <td>john@example.com</td>
        </tr>
        <tr>
          <td>Mary</td>
          <td>Moe</td>
          <td>mary@example.com</td>
        </tr>
        <tr>
          <td>July</td>
          <td>Dooley</td>
          <td>july@example.com</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- //{--------------containers-------------- -->
  <div class="container bg-danger mt-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi veniam culpa
    ratione eius deserunt iure cumque facilis vel deleniti voluptatem, fugiat numquam voluptatum voluptates? Sint
    voluptatibus magnam eum dignissimos, pariatur asperiores hic, molestiae aperiam ipsa voluptates nesciunt, sit
    laborum harum labore beatae ducimus ullam et placeat ipsum vitae tenetur voluptatum.</div>
  <div class="container-fluid bg-success">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi veniam culpa
    ratione eius deserunt iure cumque facilis vel deleniti voluptatem, fugiat numquam voluptatum voluptates? Sint
    voluptatibus magnam eum dignissimos, pariatur asperiores hic, molestiae aperiam ipsa voluptates nesciunt, sit
    laborum harum labore beatae ducimus ullam et placeat ipsum vitae tenetur voluptatum.</div>
  <div class="mx-auto mt-5" style="max-width:600px;">
    <div class=" bg-success">Lorem.</div>

  </div>

  <!-- //{--------------GRID SYSTEM-------------- -->
  <div class="container-fluid" style="margin-top:200px;">
    <h1>Basic Grid Structure</h1>
    <p>Resize the browser window to see the effect.</p>
    <p>The first, second and third row will automatically stack on top of each other when the screen is less than 576px
      wide.</p>

    <div class="container-fluid">
      <!-- Control the column width, and how they should appear on different devices -->
      <div class="row">
        <div class="col-sm-6" style="background-color:yellow;">50%</div>
        <div class="col-sm-6" style="background-color:orange;">50%</div>
      </div>
      <br>

      <div class="row">
        <div class="col-sm-4" style="background-color:yellow;">33.33%</div>
        <div class="col-sm-4" style="background-color:orange;">33.33%</div>
        <div class="col-sm-4" style="background-color:yellow;">33.33%</div>
      </div>
      <br>

      <!-- Or let Bootstrap automatically handle the layout -->
      <div class="row">
        <div class="col-sm" style="background-color:yellow;">25%</div>
        <div class="col-sm" style="background-color:orange;">25%</div>
        <div class="col-sm" style="background-color:yellow;">25%</div>
        <div class="col-sm" style="background-color:orange;">25%</div>
      </div>
      <br>

      <div class="row">
        <div class="col" style="background-color:yellow;">25%</div>
        <div class="col" style="background-color:orange;">25%</div>
        <div class="col" style="background-color:yellow;">25%</div>
        <div class="col" style="background-color:orange;">25%</div>
      </div>
    </div>
  </div>

  <!-- <div class="container mt-5">

<button class="btn btn-primary btn-sm ">click me</button>
<button class="btn btn-outline-dark btn-sm">click me</button>
<button class="btn btn-primary">click me</button>
<button class="btn btn-outline-dark">click me</button>
<button class="btn btn-primary btn-lg">click me</button>
<button class="btn btn-outline-dark btn-lg">click me</button>
<button class="btn btn-primary btn-block">click me</button>
<button class="btn btn-outline-dark btn-block">click me</button>
<button class="btn btn-primary ">click me</button>
<button class="btn btn-primary active">click me</button>
</div> -->



  <!-- 
<div class="btn-group btn-group-toggle my-4" data-toggle="buttons">
  <label class="btn btn-secondary active">
    <input type="radio" name="options" id="option1" autocomplete="off" checked> Active
  </label>
  <label class="btn btn-secondary">
    <input type="radio" name="options" id="option2" autocomplete="off"> Radio
  </label>
  <label class="btn btn-secondary">
    <input type="radio" name="options" id="option3" autocomplete="off"> Radio
  </label>
</div>
 -->




  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- bs-custom-file-input -->
  <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
</body>

</html>