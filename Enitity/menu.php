<?php
session_start();
?>

<head>
<link rel="stylesheet" type = "text/css" href="CSS/menu.css">
</head>


<div class="topnav"> 
<?php
#different inputs/hyperlinks in topnav
?>
<nav class="navbar">
  <div id="trapezoid">
   <div class="subnav">
     <button class="subnavbtn">Home<i class="fa fa-caret-down"></i></button>
       <div class="subnav-content">
        <div id="subnav-trapezoid">
          <a  href="Home.php">Home</a>
          <a href="contact.php">Contact</a>
        </div>
       </div>
    </div>

     <?php if(isset($_SESSION["Username"])) {
        if ($_SESSION["Type"] == "Consumer"){?>

          <div class="subnav">
          <button class="subnavbtn">Account<i class="fa fa-caret-down"></i></button>
            <div class="subnav-content">
              <div id="subnav-trapezoid">
                <a href="Customer.php">Account Details</a>
                <a href="booking.php">Bookings</a> 
                <a href="logout.php">Logout</a>
              </div>
            </div>
          </div>
        
        <?php } if ($_SESSION["Type"] == "Photographer"){ ?> 
          <div class="subnav">
          <button class="subnavbtn">Customers<i class="fa fa-caret-down"></i></button>
            <div class="subnav-content">
              <div id="subnav-trapezoid">
                <a href="Customer.php">Accounts</a>
                <a href="Restore.php">Deleted</a> 
              </div>
            </div>
          </div>

          <div class="subnav">
          <button class="subnavbtn">Photographers<i class="fa fa-caret-down"></i></button>
            <div class="subnav-content">
              <div id="subnav-trapezoid">
                <a href="photographer.php">Photographers</a>
                <a href="photographersignup.php">New</a>
                <a href="logout.php">Logout</a>
              </div>
            </div>
          </div>

        <?php } ?>

        <div class="subnav">
          <button class="subnavbtn" >Bookings<i class="fa fa-caret-down"></i></button>
            <div class="subnav-content">
              <div id="subnav-trapezoid">
                <a href="booking.php">View</a>
                <a href="CreateBooking.php">New</a>
                <a href="payment.php">Payment</a> 
                <?php if ($_SESSION["Type"] == "Photographer"){ ?>
                  <a href="EditBooking.php">Edit</a>
                  <a href="report.php">Report</a> 
                  <a href="Restore.php">Deleted</a> 
                <?php } ?> 
              </div>
            </div>
          </div>
    <?php } else{ ?>
     <div class="subnav">
     <button class="subnavbtn">Login<i class="fa fa-caret-down"></i></button>
       <div class="subnav-content">
        <div id="subnav-trapezoid">
          <a href="login.php">Login</a>
          <a href="signup.php">Sign Up</a>
        </div>
       </div>
    </div>
    <?php } ?>

    <div class="subnav">
     <button class="subnavbtn">About<i class="fa fa-caret-down"></i></button>
       <div class="subnav-content">
        <div id="subnav-trapezoid">
	      <a href="Portfolio.php">Portfolio</a>
          <a href="Pricing.php">Pricing</a>
        </div>
       </div>
    </div>
  
    
  </div>
</nav>
</div>


<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>

<script>
    $(function () {
        $('a').each(function () {
            if ($(this).prop('href') == window.location.href) {
                $(this).addClass('active'); $(this).parents('li').addClass('active');
            }
        });
    });
</script>

<script>
$('#trapezoid').mouseleave(function() {
  $('#trapezoid').css({
        'margin-top': '-53px'
    }); 
    
}).mouseenter(function() {
  $('#trapezoid').css({
        'margin-top': '0px'
    }); 
});
</script>




