<DOCTYPE HTML>
<?php
session_start();
	#connects page to the database
	include 'Enitity/connect.php';
	
	
	#checks if restore booking is selected
	if (isset($_GET['res1'])) {
		#assign id from res1 id for BookingID
		$id = $_GET['res1'];
		#selects record from DeletedBooking corresponding to the res1 id
		$record = mysqli_query($con, "SELECT * FROM deletedbooking WHERE BookingID='" . $id ."'");
		$n = mysqli_fetch_array($record);
		#assigns data from selected record
		$Booking = $n["BookingID"];
		$Consumer = $n["ConsumerID"];
		$Photographer = $n["PhotographerID"];
		$ShootType = $n["ShootTypeID"];
		$PackageType = $n["PackageID"];
		$Date = $n['Date'];
		$Address = $n["ShootLocation"];
		$StartTime = $n['StartTime'];
		$Length = $n['Length'];
		$Price = $n['Price'];
		$Status = $n['Status'];
		$Paid = $n['Paid'];
		
		#inserts record data into the Booking Table
		$sql = mysqli_query($con, "INSERT INTO booking (BookingID, ConsumerID, PhotographerID , ShootTypeID , PackageID , Date , ShootLocation , StartTime, Length , Price , Status, Paid) 
		VALUES ('$Booking','$Consumer','$Photographer','$ShootType','$PackageType','$Date','$Address','$StartTime','$Length','$Price','$Status','$Paid')") or die (mysqli_error($con));
		
		#deletes the booking at res1 id from the DeletedBooking table
		mysqli_query($con, "DELETE FROM deletedbooking WHERE BookingID='" . $id ."'") or (mysqli_error($con));
		$message = "Booking Restored"; }
		
	#checks if delete booking is selected
	if (isset($_GET['del1'])) {
		#assigns id from del1 id for BookingID
		$id = $_GET['del1'];
		#deletes the booking at del1 id from the DeletedBooking table
		mysqli_query($con, "DELETE FROM deletedbooking WHERE BookingID='" . $id ."'") or (mysqli_error($con));
		$message = "Booking Deleted"; }
		
	#checks if restore account is selected
	if (isset($_GET['res2'])) {
		#assign id from res2 id for ConsumerID
		$id = $_GET['res2'];
		
		#collects record from DeletedConsumer at the corresponding res2 id
		$transfer = mysqli_query($con, "SELECT * FROM deletedconsumer WHERE ConsumerID='" . $id ."'") or (mysqli_error($con));
		$d = mysqli_fetch_array($transfer);
		#assigns data from record
		$Firstname = $d["Firstname"];
		$Surname = $d["Surname"];
		$Email = $d["Email"];
		$Address2 = $d["Address"];
		$PhoneNumber = $d["PhoneNumber"];
		$LoginID = $d["LoginID"];
		$Username = $d["Username"];
		$Password = $d["Password"];
		
		#inserts record data in Consumer Table
		mysqli_query($con, "INSERT INTO consumer(ConsumerID, Firstname, Surname , Email , PhoneNumber, LoginID,Address) 
		VALUES ('$id' , '$Firstname', '$Surname' , '$Email' , '$PhoneNumber' , '$LoginID', '$Address2')") or die (mysqli_error($con));
		#inserts record data in Login Table
		mysqli_query($con, "INSERT INTO login(LoginID, Username, Password, Type) 
		VALUES ('$LoginID', '$Username', '$Password', 'Consumer')") or die (mysqli_error($con));
		#deletes the account at res2 id from the DeletedConsumer table
		mysqli_query($con, "DELETE FROM deletedconsumer WHERE ConsumerID='" . $id ."'") or (mysqli_error($con));
		$message = "Account Restored"; }
		
	#checks if delete account is selected 
	if (isset($_GET['del2'])) {
		#assigns id from del2 id for ConsumerID
		$id = $_GET['del2'];
		#deletes the account at del2 id from the DeletedConsumer table
		mysqli_query($con, "DELETE FROM DeletedConsumer WHERE ConsumerID='" . $id ."'") or (mysqli_error($con));
		$message = "Account Deleted"; }

	#redirect users that aren't logged in to the referal page
	if(!isset($_SESSION["Username"]))
		{header("Location:Refer.php");}
	#redirects consumers to the account page
	if($_SESSION["Type"]=="Consumer") {
		header("Location:Account.php");
	}
?>
<html>
<head>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
<link rel="stylesheet" type = "text/css" href="CSS/table.css">
<title>Restoration</title>
</head>

<body>
<?php
#displays navbar at the top of the page
include 'Enitity/menu.php';
#displays messages when the page is interacted with
?>

<div class="message"><?php if($message!="") { echo $message; } ?></div>

<?php 
#checks if search DeletedBooking is selected
 if (isset($_POST['_search1'])) {
	#get PackageID in the Package table
    $select2 = mysqli_query($con, "SELECT * FROM package WHERE Type='%".$_POST['search']."%'")
    or die(mysqli_error($con));
	$Package = mysqli_fetch_array($select2);
	#collects all bookings relating to search
    $query = mysqli_query($con, "SELECT * FROM deletedbooking INNER JOIN shoottype ON deletedbooking.ShootTypeID = shoottype.ShootTypeID WHERE Date LIKE'%".$_POST['search']."%' or 
	ShootLocation LIKE'%".$_POST['search']."%' or StartTime LIKE'%".$_POST['search']."%' or Price LIKE'%".$_POST['search']."%' 
	or Status LIKE'%".$_POST['search']."%' or Type LIKE'%".$_POST['search']."%' or PackageID='".$Package['PackageID']."' or Paid LIKE'%".$_POST['search']."%'")
    or die(mysqli_error($con));
}
else{
	#collects all bookings
	$query = mysqli_query($con, "SELECT * FROM deletedbooking")
   or die (mysqli_error($con));
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
<button class="btn" type="submit" name="_search1" style="display: block; margin-left: auto;
    margin-right: auto; width: 8em">Search</button>
</div>
</form>

<?php #display the table ?>
<table>
	<thead>
		<tr>
       		<th>ID</th>
			<th>Date</th>
			<th>Shoot Location</th>
			<th>StartTime</th>
			<th>Length</th>
			<th>Price</th>
            <th>Price Status</th>
            <th>Paid</th>
			<th colspan="5">Action</th>
		</tr>
	</thead>
	
	<?php 
	#insert the records selected from the booking table into the displayed table
	while ($row = mysqli_fetch_array($query)) {
		#output data in row array
		echo
  		 "<tr>
		    <td>{$row['Date']}</td>
		    <td>{$row['ShootLocation']}</td>
			<td>{$row['StartTime']}</td>
			<td>{$row['Length']}</td>
			<td>{$row['Price']}</td>
			<td>{$row['Status']}</td>
			<td>{$row['Paid']}</td>";
			#adds a restore button to restore bookings
			?>
			<td>
				<a onclick="javascript:confirmationRestoreBook($(this));return false;" href="Restore.php?res1=<?php echo $row['BookingID']; ?>" class="del_btn" style="background: #096;">Restore</a>
			</td>
			<?php #adds a delete button to delete bookings ?>
            <td>
				<a onclick="javascript:confirmationDeleteBook($(this));return false;" href="Restore.php?del1=<?php echo $row['BookingID']; ?>" class="del_btn">Delete</a>
			</td>
		</tr>
	<?php }?>
</table>


<?php
#checks if search DeleteConsumer is selected
if (isset($_POST['_search2'])) {
	#collects all consumers relating to search
	$query = mysqli_query($con, "SELECT * FROM deletedconsumer WHERE (ConsumerID='".$_POST['search']."' or 
	Firstname LIKE'%".$_POST['search']."%' or Surname LIKE'%".$_POST['search']."%' or PhoneNumber LIKE'%".$_POST['search']."%' 
	or Address LIKE'%".$_POST['search']."%' or Email LIKE'%".$_POST['search']."%' or LoginID ='".$_POST['LoginID']."')")
	or die(mysqli_error($con));
}
else{
	#collects all consumers
    $query = mysqli_query($con, "SELECT * FROM deletedconsumer")
   or die(mysqli_error($con));
   }?>

<form action="" method="post" align="center" style="background-color:transparent;
	border: solid transparent";>
<div class="input-group">
<input name="search" type="text" placeholder="Type here" style="height: 30px;
    width: 100%; font-size: 16px;">
</div>
<div class="input-group">
<button class="btn" type="submit" name="_search2" style="display: block; margin-left: auto;
    margin-right: auto; width: 8em">Search</button>
</div>
</form>

<?php #display the table ?>
<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Firstname</th>
			<th>Surname</th>
			<th>Address</th>
			<th>PhoneNumber</th>
			<th>Email</th>
            <th>Username</th>
			<th colspan="5">Action</th>
		</tr>
	</thead>
	
	<?php 
	#insert the records selected from the DeletedConsumer table into the displayed table
	while ($row = mysqli_fetch_array($query)) {
		echo
  		 "<tr>
			<td>{$row['ConsumerID']}</td>
   		    <td>{$row['Firstname']}</td>
			<td>{$row['Surname']}</td>
		    <td>{$row['Address']}</td>
    		<td>{$row['PhoneNumber']}</td>
			<td>{$row['Email']}</td>
			<td>{$row['Username']}</td>";
			#adds an restore button to restore accounts
			?>
			<td>
				<a onclick="javascript:confirmationRestoreCust($(this));return false;" href="Restore.php?res2=<?php echo $row['ConsumerID']; ?>" class="del_btn" style="background: #096;">Restore</a>
			</td>
			<?php #adds a delete button to delete accounts ?>
            <td>
				<a onclick="javascript:confirmationDeleteCust($(this));return false;" href="Restore.php?del2=<?php echo $row['ConsumerID']; ?>" class="del_btn">Delete</a>
			</td>
		</tr>
	<?php }?>
</table>


<br><br><br>
</body>
</html>

<script>
function confirmationDeleteCust(anchor)
{
   var conf = confirm('Are you sure want to permanently delete this customer account?');
   if(conf)
      window.location=anchor.attr("href");
}

function confirmationDeleteBook(anchor)
{
   var conf = confirm('Are you sure want to permanently delete this booking?');
   if(conf)
      window.location=anchor.attr("href");
}

function confirmationRestoreCust(anchor)
{
   var conf = confirm('Are you sure want to restore this customer account?');
   if(conf)
      window.location=anchor.attr("href");
}

function confirmationRestoreBook(anchor)
{
   var conf = confirm('Are you sure want to restore this booking?');
   if(conf)
      window.location=anchor.attr("href");
}
</script>
