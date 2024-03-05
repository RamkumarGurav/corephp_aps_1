  <!-- Notification Toast using BS4 -->
  <?php if (isset($_SESSION["toast_message"])) : ?>
  <div id="customToast"
    class="d-none alert  <?= $_SESSION["toast_type"] ? $_SESSION["toast_type"] : "alert-primary" ?> alert-dismissible position-fixed bottom-0 right-0 align-items-center border-0 zindex-tooltip"
    style="bottom:40px;right:10px;max-width:600px;z-index:2000;" role="alert" aria-live="assertive" aria-atomic="true">
    <button id="customToastClose" type="button" class="close text-dark font-weight-bolder"
      data-dismiss="alert">×</button>
    <?= $_SESSION["toast_message"] ?>


  </div>
  <?php $_SESSION["toast_message"] = null;
    $_SESSION["toast_type"] = null;
    ?>
  <?php endif; ?>



  <!-- FOR FRONTEND---------------->
  <div id="customToastFE"
    class="d-none alert  alert-dismissible position-fixed bottom-0 right-0 align-items-center border-0 zindex-tooltip"
    style="bottom:40px;right:10px;max-width:600px;z-index:2000;" role="alert" aria-live="assertive" aria-atomic="true">
    <button type="button" id="customToastFEClose" class="close text-dark font-weight-bolder"
      data-dismiss="alert">×</button>
  </div>
  <!-- //--------------------------------------------------} -->


  <script>
$(function() {

  // Get the toast element
  var toastElement = $("#customToast");
  // Add "hide" class to the toast after 2 seconds
  setTimeout(function() {
    toastElement.removeClass("d-none");
  }, 700);
  setTimeout(function() {
    $('#customToastClose').alert("close");

  }, 4000);



});
  </script>