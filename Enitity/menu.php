<?php
#creates class to put navigation bar in
?>
<div class="topnav"> 
<?php
#different inputs/hyperlinks in topnav
?>
	<a class="active" href="Home.php">Home</a>
    <?php if($_SESSION["Username"]) {?>
    <a align="right" href="Account.php">Account</a> 
	<a href="booking.php">Bookings</a> 
    <a href="logout.php">Logout</a> <?php }		
	else {?>
	<a href="login.php">Login</a>
    <a href="signup.php">Sign Up</a> <?php }?>
    <a href="contact.php">Contact</a>
	<a href="Portfolio.php">Portfolio</a>
    <a href="Pricing.php">Pricing</a>
</div>
<?php
#links to stylesheet to determine the design of topnav
?>

<head>
<link rel="stylesheet" type = "text/css" href="CSS/menu.css">
</head>



