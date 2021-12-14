<DOCTYPE HTML>
<?php
session_start();
?>
<html>
<?php

#connects page to database
include 'Enitity/connect.php';
# gets the name of the ShootType for the corresponding ShootTypeID in the ShootType Table
if (isset($_SESSION["Date"])){
$collect1 = mysqli_query($con, "SELECT * FROM shoottype WHERE ShootTypeID='".$_SESSION["ShootID"]."'") or die(mysqli_error($con));
$Shootings = mysqli_fetch_array($collect1);
# gets the name of the Package for the corresponding PackageID in the Package Table
$collect2 = mysqli_query($con, "SELECT * FROM package WHERE PackageID='".$_SESSION['PackageID']."'") or die(mysqli_error($con));
$Packagings = mysqli_fetch_array($collect2);
#Collects the BookingID from the Booking table for this booking
$collect3 = mysqli_query($con, "SELECT * FROM booking WHERE Date='".$_SESSION['Date']."' and ShootLocation='".$_SESSION['Address']."' and StartTime='".$_SESSION['StartTime']."'") or die(mysqli_error($con));}
$Bookings = mysqli_fetch_array($collect3);
?>
<head>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
<link rel="stylesheet" type = "text/css" href="CSS/Table.css">
<title>About Page</title>
</head>

<body>

<?php # displays the Booking details of the booking ?>
<div name = "container" align = "center">
<h1>Invoice</h1>
<br>
Date: <?php echo $_SESSION["Date"]; ?>
<p>
Address : <?php echo $_SESSION["Address"]; ?>
<p>
Package: <?php echo $Packagings["Type"]; ?>
<p>
ShootType: <?php echo $Shootings["Type"]; ?>
<p>
StartTime : <?php echo $_SESSION["StartTime"]; ?>
<p>
Length : <?php echo $_SESSION["Length"]; ?>
<p>
Price : <?php echo $_SESSION["Price"]; ?>
<p>
Your Booking Reference Number is # <?php echo $Bookings["BookingID"] ?>
<br><br><br><br>
Thanks for making a booking with us, to view this booking and other bookings make sure to head to our bookings page.
<p>
If you would like to make any changes to your booking or have any enquiries feel free to get in contact with us via our contact page, email or over the phone.
<br><br>

<?php # a button to direct users to the account page ?>
<a href="Account.php" class="btn" type="submit" name="Account" style="background: #008080;">Account</a>
</div>

<?php
# unsets all of the data in Session pertaining to this booking
unset($_SESSION["Date"]);
unset($_SESSION["Address"]);
unset($_SESSION["PackageID"]);
unset($_SESSION["ShootID"]);
unset($_SESSION["StartTime"]);
unset($_SESSION["Length"]);
unset($_SESSION["Price"]);
?>
 <br><br><br>
</body>
</html>

