<html>
<?php

include 'Enitity/Header.php';

session_start();
include 'Enitity/connect.php';
$message="";

#checks if the del class button (cancel) is selected
if (isset($_GET['del'])) {
		$id = $_GET['del'];
		$state = "Cancelled";
		#change booking status to cancelled in booking table
		mysqli_query($con, "UPDATE Booking SET Status='$state' WHERE BookingID='".$id."'") or die (mysqli_error($con));
		$message = "Booking Cancelled"; }
		
		
#redirect users that aren't logged in to the referal page
if($_SESSION["Username"]){}
else
{ header("Location:Refer.php");}
?>

<head>
<meta charset="utf-8">
<title>Booking Page</title>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
<link rel="stylesheet" type = "text/css" href="CSS/table.css">
</head>

<body>
<?php
include 'Enitity/menu.php';
?>
<h1 style="text-align:center">Bookings</h1>
<?php
include 'Enitity/connect.php';

#select from bookings for that photographer
if ($_SESSION["Type"] === "Photographer") {
	#select bookings from search for that photographer
    if (isset($_POST['_search'])) {
		#get ShootTypeID from search in ShootType table
		$select = mysqli_query($con, "SELECT * FROM ShootType WHERE Type LIKE '%".$_POST['search']."%'")
		or die(mysqli_error($con));
		$Shoot = mysqli_fetch_array($select);

		#select bookings related to search
        $query = mysqli_query($con, "SELECT * FROM Booking INNER JOIN Package ON Booking.PackageID = Package.PackageID WHERE Date LIKE'%".$_POST['search']."%' 
		or ShootLocation LIKE'%".$_POST['search']."%' or StartTime LIKE'%".$_POST['search']."%' or Price LIKE'%".$_POST['search']."%' or 
		Status LIKE'%".$_POST['search']."%' or ShootTypeID='".$Shoot['ShootTypeID']."' or Type LIKE '%".$_POST['search']."%' or 
		Paid LIKE'%".$_POST['search']."%'")or die(mysqli_error($con));}
	else {
		#select all bookings for that photographer
        $query = mysqli_query($con, "SELECT * FROM Booking WHERE PhotographerID='". $_SESSION["ID"]."'")
   		or die(mysqli_error($con));}}
   
#select from bookings for that consumer
if ($_SESSION["Type"] === "Consumer") {
	#select bookings from search for that consumer
    if (isset($_POST['_search'])) {
		#get ShootTypeID from search in ShootType table
		$select = mysqli_query($con, "SELECT * FROM ShootType WHERE Type LIKE '%".$_POST['search']."%'")
		or die(mysqli_error($con));
		$Shoot = mysqli_fetch_array($select);

		#select bookings related to search for that consumer
		$query = mysqli_query($con, "SELECT * FROM Booking INNER JOIN Package ON Booking.PackageID = Package.PackageID WHERE ConsumerID='". $_SESSION["ID"]."' 
		and (Date LIKE'%".$_POST['search']."%' or Address LIKE'%".$_POST['search']."%' or StartTime LIKE'%".$_POST['search']."%' or 
		Price LIKE'%".$_POST['search']."%' or Status LIKE'%".$_POST['search']."%' or ShootTypeID='".$Shoot['ShootTypeID']."' or 
		PackageID='".$_POST['search']."' or Paid LIKE'%".$_POST['search']."%')")or die(mysqli_error($con));
    } else {
		#select all bookings for that consumer
        $query = mysqli_query($con, "SELECT * FROM Booking WHERE ConsumerID='". $_SESSION["ID"]."'")
   or die(mysqli_error($con));
    }
}
#creates a search bar
?>
<form action="" method="post" align="center" style="background-color:transparent;
	border: solid transparent";>
<div class="input-group">
<input name="search" type="text" placeholder="Type here" style="height: 30px;
    width: 100%; font-size: 16px;">
</div>
<div class="input-group">
<button class="btn" type="submit" name="_search" style="display: block; margin-left: auto;
    margin-right: auto; width: 8em">Search</button>
</div>
</form>

<?php #displays message?>
<div class="message"><?php if($message!="") { echo $message; } ?></div>

<?php #display the table?>
<table>
	<thead>
		<tr>
			<th>Reference</th>
			<th>Date</th>
			<th>Shoot Location</th>
			<th>StartTime</th>
			<th>Length</th>
			<th>Price</th>
            <th>Shoot Type</th>
            <th>Package</th>
            <th>Price Status</th>
            <th>Paid</th>
            <th colspan="5">Action</th>
		</tr>
	</thead>
	
	<?php 
	#insert the records selected from the booking table into the displayed table
	while ($row = mysqli_fetch_array($query)) {
		$collect1 = mysqli_query($con, "SELECT * FROM ShootType WHERE ShootTypeID='".$row["ShootTypeID"]."'") or die(mysqli_error($con));
		$Shootings = mysqli_fetch_array($collect1);
		$collect2 = mysqli_query($con, "SELECT * FROM Package WHERE PackageID='".$row['PackageID']."'") or die(mysqli_error($con));
		$Packagings = mysqli_fetch_array($collect2);
		echo
		   "<tr>
		   <td>{$row['BookingID']}</td>
		   <td>{$row['Date']}</td>
		   <td>{$row['ShootLocation']}</td>
		   <td>{$row['StartTime']}</td>
		   <td>{$row['Length']}</td>
		   <td>{$row['Price']}</td>
		   <td>{$Shootings['Type']}</td>
		   <td>{$Packagings['Type']}</td>
		   <td>{$row['Status']}</td>
		   <td>{$row['Paid']}</td>
   			";
			#adds a cancel button to cancel bookings
			?>
            <td>
				<a href="booking.php?del=<?php echo $row['BookingID']; ?>" class="del_btn">Cancel</a>
			</td>
		</tr>
	<?php }?>
</table>

<div class="container" align="center">
<?php
#creates buttons to link to other pages
if ($_SESSION["Type"] == "Photographer"){ ?>
<a href="EditBooking.php" class="btn" type="submit" name="EditBooking" style="background: #2f9651; border: 1px solid #21703b;">Edit Booking</a>
<a href="report.php" class="btn" type="submit" name="booking" style="background: #26184a;">Generate Report</a>
<?php }?>
<a href="CreateBooking.php" class="btn" type="submit" name="CreateBooking" style="background: #531a88; border: 1px solid #330e54;">Create New Booking</a>
<a href="payment.php" class="btn" type="submit" name="Payment" style="background: #1c4966;  border: 1px solid #102a3b;">Pay for Booking</a>
</div>

<br><br><br>
</body>
<?php
include 'Enitity/Footer.php';
?>
</html>




