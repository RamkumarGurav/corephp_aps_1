<?php


include("admin/controller/Album.php");
include("admin/controller/Gallery.php");

//{--------------FRONTEND--------------
$fe_album_details = null;
$fe_year_details = null;
$fe_gallery_images = null;
$fe_album_id = null;
if (!empty($_SERVER['QUERY_STRING'])) {
  $fe_album_id = explode("=", $_SERVER['QUERY_STRING'])[1];
  $fe_album_details =  $album_controller->findOne($fe_album_id);
  $fe_year_details =  $fy_controller->findOne($fe_album_details['year_id']);
  $fe_gallery_images = $gallery_controller->findAll("album_id", $fe_album_id);
}



//--------------------------------------------------}



?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>Appolo Public Schoool</title>
  <link href="favicon.ico" rel="shortcut icon" type="x-image">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/custome.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <!--<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700|Poppins:300,400,500,600,700|Raleway:300,400,500,600,700" rel="stylesheet">-->
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700|Poppins:300,400,500,600,700|Raleway:300,400,500,600,700" rel="stylesheet">
  <link rel="stylesheet" href="toastr/toastr.min.css">
  <style>
    .err-bd {
      border: 1px solid #c21c20 !important;
    }
  </style>
</head>

<body>

  <div class="floating-form float_formw" id="contact_form">
    <h4 class="form_head_1">Enquire With Us</h4>
    <button class="close_btn_1 contact-opener">X</button>
    <div id="contact_body">
      <div class="contact-form">
        <form name="enqForm" id="SideContact-us" action="controllers/doEnq.php" accept-charset="utf-8" autocomplete="off" enctype="multipart/form-data" method="POST" class="contact-form">
          <input type="hidden" name="enq_type" value="SideContact-us">
          <input type="hidden" name="pagelink" value="saraswati-pooja.html">
          <div class="form-group">
            <input type="text" id="SideContact-us-name" name="name_sidecontact_us" pattern="(?=.*[A-Za-z])[A-Za-z\s]*" required="required" class="enqur_form1" placeholder="Name*" />
          </div>
          <div class="form-group">
            <input type="email" id="SideContact-us-email" name="email_sidecontact_us" class="enqur_form1" required="required" placeholder="Email Address*" />
          </div>
          <div class="form-group">
            <input type="number" id="SideContact-us-contact" name="contact_sidecontact_us" class="enqur_form1" required="required" placeholder="Contact No*" />
          </div>
          <div class="form-group">
            <textarea name="message_sidecontact_us" id="SideContact-us-message" class="enqur_form1 textarea" required="required" placeholder="Message*"></textarea>
          </div>
          <div class="form-group">
            <button class="g-recaptcha banner_btn2" data-sitekey="6LcxrdYmAAAAAKI2wq9VxJdefXSEeSKwMNpbFiQo" data-callback='onSubmit1' data-action='submit'>Send Message</button>
          </div>
          <div class="clear"></div>
        </form>
      </div>
    </div>
  </div>
  <div class="contact-opener form_open_b"><span>Admissions Open 2024-25</span></div>

  <div class="main_banner">
    <header class="sub_navbar1">
      <header class="sub_navbar2">
        <div class="container">
          <ul class="header_nav1">
            <li><i class="fa fa-envelope-o"></i>appolopublicsch@gmail.com</li>
            <li><i class="fa fa-phone"></i>+91 80 2669 8908/09 / +91 8792539589</li>
          </ul>
          <ul class="header_nav2">
            <li><a href="contact-us.html">Contact Us</a></li>
          </ul>
        </div>
      </header>
      <header class="sub_navbar3">
        <nav class="navbar navbar-default bootsnav" data-spy="affix" data-offset-top="60">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu"><i class="fa fa-bars"></i></button>
              <a class="navbar-brand" href="index.html"><img src="images/logo.png" class="logo_mwidth" alt=""></a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-menu">
              <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
                <li><a href="index.html">Home</a></li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown">About Us<i class="fa fa-caret-down"></i></a>
                  <ul class="dropdown-menu pull-right">
                    <li><a href="about-us.html">Overview</a></li>
                    <li><a href="mission-vision.html">Our Vision / Mission</a></li>
                    <li><a href="admission-procedure.html">Admission Procedure</a></li>
                  </ul>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown">Messages<i class="fa fa-caret-down"></i></a>
                  <ul class="dropdown-menu pull-right">
                    <li><a href="chairmans-message.html">Chairperson Message</a></li>
                    <li><a href="vice-chairmans-message.html">Vice Chairperson Message</a></li>
                    <li><a href="principals-message.html">Principal's Message</a></li>
                    <li><a href="trustees-message.html">Trustee's Message</a></li>
                  </ul>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown">Academics <i class="fa fa-caret-down"></i></a>
                  <ul class="dropdown-menu pull-right">
                    <li><a href="curriculum.html">Curriculum</a></li>
                    <li><a href="co-curricular-activities.html">Co-Curricular Activities</a></li>
                    <li><a href="school-timings.html">School Timings</a></li>
                    <li><a href="academic-calender.html">Academic Calender</a></li>
                    <li><a href="holiday-list.html">List of Holidays</a></li>
                  </ul>
                </li>
                <li><a href="facilities.html">Facilities</a></li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown">Gallery <i class="fa fa-caret-down"></i></a>
                  <ul class="dropdown-menu pull-right">
                    <?php foreach ($years as $year) : ?>
                      <li><a href="<?= "gallery.php?fy={$year["fiscal_year"]}" ?>"><?= $year["fiscal_year"] ?></a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </li>
                <li><a href="news-events.html">News & Events</a></li>

              </ul>
            </div><!-- /.navbar-collapse -->
          </div>
        </nav>
      </header>
    </header>
    <div class="sub_banner1n">
      <div class="container">
        <marquee class="scrolling_text">
          <h3>"Admissions Open 2024-25"</h3>
        </marquee>
      </div>
    </div>

    <div class="clearfix"></div>
    <div class="sub_banner2">
      <div class="container">
        <h3>Gallery</h3>
        <ul class="banner_nav1">
          <li><a href="index.html">Home</a></li>
          <li><span>-</span></li>
          <li>Gallery</li>
          <li><span>-</span></li>
          <li><?= $fe_album_details['name'] ?></li>
        </ul>
      </div>
    </div>
  </div>


  <div class="sub_featur4 eventwith_form">
    <div class="row">
      <div class="col-md-12">
        <h2 class="main_headr1 text-left  animated wow slideInUp"><?= $fe_album_details['name'] ?>
          <span><?= $fe_year_details['fiscal_year'] ?> </span>
        </h2>
        <span class="border_line  text-left  animated wow slideInUp"></span>
        <div class="row">

          <?php if (empty($fe_gallery_images)) : ?>
            <p>Empty</p>
          <?php else : ?>
            <?php foreach ($fe_gallery_images as $fe_gallery_image) : ?>
              <div class="events_col2  animated wow slideInUp">
                <div class="events_colw">
                  <div class="events_imag">
                    <a class="example-image-link" href="<?= "uploads/album/{$fe_year_details['fiscal_year']}/album_images/{$fe_gallery_image['album_image']}" ?>" data-lightbox="example-1" title="<?= $fe_album_details['name'] ?>">
                      <img class="example-image img-responsive" src="<?= "uploads/album/{$fe_year_details['fiscal_year']}/album_images/{$fe_gallery_image['album_image']}" ?>" alt="<?= $fe_album_details['name'] ?>" title="<?= $fe_album_details['name'] ?>" />
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>




        </div>
      </div>

    </div>
  </div>

  <footer class="main_footer">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12 animated wow slideInUp" data-wow-duration="1s">
          <h4 class="footer_head">Quick Links</h4>
          <ul class="footer_nav2">
            <li><a href="index.html"><i class="fa fa-angle-right"></i> Home</a></li>
            <li><a href="about-us.html"><i class="fa fa-angle-right"></i> About Us</a></li>
            <li><a href="chairmans-message.html"><i class="fa fa-angle-right"></i> Messages</a></li>
            <li><a href="curriculum.html"><i class="fa fa-angle-right"></i> Academics</a></li>
          </ul>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 animated wow slideInUp" data-wow-duration="2s">
          <h4 class="footer_head">&nbsp</h4>
          <ul class="footer_nav2">
            <li><a href="news-events.html"><i class="fa fa-angle-right"></i> News & Events</a></li>
            <li><a href="facilities.html"><i class="fa fa-angle-right"></i> Facilities</a></li>
            <li><a href="gallery-2022-23.html"><i class="fa fa-angle-right"></i> Gallery</a></li>
            <li><a href="contact-us.html"><i class="fa fa-angle-right"></i> Contact Us</a></li>
          </ul>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 animated wow slideInUp" data-wow-duration="1s">
          <h4 class="footer_head">Contact Us</h4>
          <ul class="footer_nav1">
            <li><i class="fa fa-map-marker"></i># 320, 5th Cross, 5th Block, Banashankari,
              3rd Stage, 3rd Phase, Bengaluru-560085</li>
            <li><i class="fa fa-phone"></i>+91 80 2669 8908/09<br>+91 8792539589</li>
            <li><i class="fa fa-envelope"></i>appolopublicsch@gmail.com</li>
          </ul>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 animated wow slideInUp" data-wow-duration="2s">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3888.7324382422685!2d77.55048051482149!3d12.92491069088659!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb6ab8f59d27ee566!2sAppolo%20Public%20School!5e0!3m2!1sen!2sin!4v1639141357781!5m2!1sen!2sin" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>
    </div>
  </footer>

  <footer class="main_copyrt">
    <div class="container">
      <p class="main_parag1">© Copyright 2021 <span>Appolo Public School</span>. All Rights Reserved. </p>
      <ul class="footer_nav3">
        <li style="float:right"><a href="https://www.marswebsolution.com/">Design By : Mars Web Solutions</a></li>
      </ul>
    </div>
  </footer>
  <a href="#0" class="cd-top">Top</a>

  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/menubar.js"></script>
  <script src="js/counter.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/index.js"></script>
  <script src="js/back-to-top.js"></script>
  <script src='js/wow.js'></script>
  <script src='js/lightbox-plus-jquery.min.js'></script>
  <script>
    wow = new WOW({
      animateClass: 'animated',
      offset: 100,
      callback: function(box) {
        console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
      }
    });
    wow.init();
    document.getElementById('moar').onclick = function() {
      var section = document.createElement('section');
      section.className = 'section--purple wow fadeInDown';
      this.parentNode.insertBefore(section, this);
    };
  </script>


  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script src="toastr/toastr.min.js"></script>
  <script>
    function onSubmit1(token) {
      //document.getElementById("demo-form").submit();
      contactvalidateForm1();
    }
  </script>
  <script>
    function contactvalidateForm1() {
      var phonepatern = /^\d{10,12}$/;
      var emailReg = /^([a-zA-Z0-9_\.\-])+\@(([0-9a-zA-Z\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

      var name = $('#SideContact-us-name').val();
      var email = $('#SideContact-us-email').val();
      var contact = $('#SideContact-us-contact').val();
      var message = $('#SideContact-us-message').val();
      //var img = $('#myfile').val();
      //alert(destinations);
      var els = document.getElementsByClassName('err-bd');
      while (els[0]) {
        els[0].classList.remove('err-bd')
      }

      //var response = grecaptcha.getResponse(html_element_id2);	
      if (name == '') {
        toastr.error("Please enter your name");
        $('#SideContact-us-name').addClass("err-bd");
        $('#SideContact-us-name').focus();
      } else if (email == '') {
        toastr.error("Please enter email");
        $('#SideContact-us-email').addClass("err-bd");
        $('#SideContact-us-email').focus();
      } else if (!emailReg.test(email)) {
        toastr.error("Invalid email id");
        $('#SideContact-us-email').addClass("err-bd");
        $('#SideContact-us-email').focus();
      } else if (contact == '') {
        toastr.error("Please enter mobile number");
        $('#SideContact-us-contact').addClass("err-bd");
        $('#SideContact-us-contact').focus();
      } else if (!phonepatern.test(contact)) {
        toastr.error("Invalid phone number");
        $('#SideContact-us-contact').addClass("err-bd");
        $('#SideContact-us-contact').focus();
      } else if (message == '') {
        toastr.error("Please enter your message / requirement");
        $('#SideContact-us-message').addClass("err-bd");
        $('#SideContact-us-message').focus();
      }
      //else if(response.length == 0){
      //toastr.error("You can't proceed! captcha validation failed.");
      //}
      else {
        $('#SideContact-us').submit();
        //document.getElementById("enq_form").submit();
        return true;
      }
      return false;
    }
  </script>

</body>

</html>