<?php 
	session_start();
	include 'Enitity/connect.php';
	#initialise variables
	$ShootType = "";
	$PackageType = "";
	$Date = "";
	$Address = "";
	$StartTime = "";
	$Length = "";
	$Price = "";
	$ID = $_SESSION['ID'];
	$id = 0;
	$update = false;
	$message="";

	#checks if the edit class button is selected
	if (isset($_GET['edit'])) {
		#assigns id from the edit id
		$id = $_GET['edit'];
		#assigns id to be used on update
		$_SESSION['edit'] = $id;
		#collects record from the BookingID at id
		$record = mysqli_query($con, "SELECT * FROM Booking WHERE BookingID='" . $id ."'");
		$n = mysqli_fetch_array($record);

		#collects shoottype name at record from collected ShootTypeID in ShootType table
		$ShootType = $n["ShootTypeID"];
		$Shooting = mysqli_query($con, "SELECT * FROM ShootType WHERE ShootTypeID='" . $ShootType ."'");
		$n1 = mysqli_fetch_array($Shooting);
		$ShootID = $n1["Type"];
		
		#collects package name at record from collected PackageID in Package table
		$PackageType = $n["PackageID"];
		$Packaging = mysqli_query($con, "SELECT * FROM Package WHERE PackageID='" . $PackageType ."'");
		$n2 = mysqli_fetch_array($Packaging);
		$Package = $n2["Type"];
		
		#assigns data from record
		$Date = $n['Date'];
		$Address = $n["ShootLocation"];
		$StartTime = $n['StartTime'];
		$Length = $n['Length'];
		$Price = $n['Price'];
		$Status = $n['Status'];
		$Paid = $n['Paid'];
		$update = true;}		
			
	#checks if the update is selected
	if (isset($_POST['update'])) {
		#gets edit id for edit
		$id = $_SESSION['edit'];
		#assigns data collected from form 
		$ShootType = $_POST['ShootType'];
		$PackageType = $_POST['PackageType'];
		$Date = $_POST['Date'];
		$Address = $_POST['Address'];
		$StartTime = $_POST['StartTime'];
		$Length = $_POST['Length'];
		$Price = $_POST['Price'];
		$Status = $_POST['Status'];
		$Paid = $_POST['Paid'];
		
		#validates inputted values
		if (($ShootType == "") or ($Date == "") or ($Address == "" ) or ($StartTime == "") or ($Length == "") or ($Price == "" ) or ($Status == "") or ($PackageType == "")){
		$message = "Please fill in all of the fields";}
		else if (!is_numeric($Length))
		{$message = "Invalid Value for Shoot Length Field";}
		else{
		#updates record in booking table at the edit id/BookingID selected
		mysqli_query($con, "UPDATE Booking SET ShootTypeID='$ShootType', PackageID='$PackageType', Date='$Date', ShootLocation='$Address', StartTime='$StartTime', Length='$Length', 
		Price='$Price',Status='$Status',Paid='$Paid' WHERE BookingID='".$id."'") or die (mysqli_error($con));
		$message = "Data updated";
		#clears variables
		$ShootType = "";
		$PackageType = "";
		$Date = "";
		$Address = "";
		$StartTime = "";
		$Length = "";
		$Price = "";
		$Paid = "";
		$_SESSION['edit'] = "";
		$update = false;}}
	
	#checks if the del class button is selected
	if (isset($_GET['del'])) {
		#assigns id from the del id
		$id = $_GET['del'];
		#collects record at the corresponding BookingID
		$record = mysqli_query($con, "SELECT * FROM Booking WHERE BookingID='" . $id ."'");
		$n = mysqli_fetch_array($record);
		#assigns data from collected record
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
		
		#backups record by inserting record into DeletedBooking Table
		mysqli_query($con, "INSERT INTO DeletedBooking (BookingID, ConsumerID, PhotographerID , ShootTypeID , PackageID , Date , ShootLocation , StartTime, Length , Price , Status, Paid) 
		VALUES ('$Booking','$Consumer','$Photographer','$ShootType','$PackageType','$Date','$Address','$StartTime','$Length','$Price','$Status', '$Paid')") or die (mysqli_error($con));
		
		#deletes record in the Booking Table at the corresponding BookingID
		mysqli_query($con, "DELETE FROM Booking WHERE BookingID='" . $id ."'") or (mysqli_error($con));
		$message = "Data deleted"; }

	#redirect users that aren't logged in to the referal page
	if($_SESSION["Username"]){}
	else
		{header("Location:Refer.php");}

	#redirect users that are consumers to the account page
	if($_SESSION["Type"] == "Consumer")
		{header("Location:Account.php");}
	
?>

<html>
<head>
<meta charset="utf-8">
<title>Edit Bookings</title>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
<link rel="stylesheet" type = "text/css" href="CSS/table.css">
</head>

<body>
<?php
#sets navbar at the top of the page
include 'Enitity/menu.php';
?>
<h1 style="text-align:center">Edit Booking</h1>

<?php 
#checks if search is selected
 if (isset($_POST['_search'])) {
	#get PackageIDin the Package table
    $select = mysqli_query($con, "SELECT * FROM Package WHERE Type LIKE '%".$_POST['search']."%'")
    or die(mysqli_error($con));
	$Package = mysqli_fetch_array($select);
	#collects all bookings relating to search
    $query = mysqli_query($con, "SELECT * FROM Booking INNER JOIN ShootType ON Booking.ShootTypeID = ShootType.ShootTypeID WHERE Date LIKE'%".$_POST['search']."%' or 
	ShootLocation LIKE'%".$_POST['search']."%' or StartTime LIKE'%".$_POST['search']."%' or Price LIKE'%".$_POST['search']."%' 
	or Status LIKE'%".$_POST['search']."%' or Type LIKE'%".$_POST['search']."%' or PackageID='".$Package['PackageID']."' or Paid LIKE'%".$_POST['search']."%'")
    or die(mysqli_error($con));}
else{
	#collects all bookings
	$query = mysqli_query($con, "SELECT * FROM Booking")
   or die (mysqli_error($con));}
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

</div>
<?php #display the table ?>
<table>
	<thead>
		<tr>
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
		#collects name for the corresponding ShootTypeID in the row array from the ShootType Table
		$collect1 = mysqli_query($con, "SELECT * FROM ShootType WHERE ShootTypeID='".$row["ShootTypeID"]."'") or die(mysqli_error($con));
		$Shootings = mysqli_fetch_array($collect1);
		#collects name for the corresponding PackageID in the row array from the Package Table
		$collect2 = mysqli_query($con, "SELECT * FROM Package WHERE PackageID='".$row['PackageID']."'") or die(mysqli_error($con));
		$Packagings = mysqli_fetch_array($collect2);
		#output data in row array
		echo
  		"<tr>
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
			#adds a edit button to edit bookings
			?>
			<td>
				<a href="EditBooking.php?edit=<?php echo $row['BookingID']; ?>" class="edit_btn">Edit</a>
			</td>
			<?php #adds a delete button to delete bookings ?>
			<td>
				<a href="EditBooking.php?del=<?php echo $row['BookingID']; ?>" class="del_btn">Delete</a>
			</td>
		</tr>
	<?php }?>
</table>

<?php
#shows form only if edit is selected
if ($update == true){
	#creates the form
?>
	<form method="post" action="EditBooking.php" align="center">
	<?php #displays message?>
	<div class="message"><?php if($message!="") { echo $message; } ?></div>
		<div class="input-group">
			<label>Shoot Type</label>
 			<select name="ShootType">
			<?php #prefills field with collected ShootID ?>
            <option value= "<?php echo $ShootType ?>"> <?php echo $ShootID;?></option>
  			<option value="0">Corporate Headshots</option>
  			<option value="1">Portrait Photography</option>
 			<option value="2">Business Portraits</option>
  			<option value="3">Corporate Lifestyle Photography</option>
 			<option value="4">Corporate Event Photography</option>
  			<option value="5">Exhibition and Conference Photography</option>
  			<option value="6">Lifestyle Photography</option>
  			<option value="7">Event Photography</option>
  			<option value="8">Scientific Photography</option>
		</select>
		</div>
        <div class="input-group">
			<label>Package Type</label>
 			<select name="PackageType">
			 <?php #prefills field with collected Package ?>
            <option value= <?php echo $PackageType ?> > <?php echo $Package;?></option>
 			<option value="1">Standard</option>
  			<option value="2">Photo+</option>
  			<option value="3">Photo Max</option>
  			<option value="4">Studio</option>
			</select>
		</div>
		<div class="input-group">
			<label>Date</label>
			<?php #prefills field with collected Date ?>
			<input type="date" name="Date" value="<?php echo $Date;?>">
		</div>
		<div class="input-group">
			<label>Shoot Address</label>
			<?php #prefills field with collected Address ?>
			<input type="text" name="Address" value="<?php echo $Address;?>">
		</div>
		<div class="input-group">
			<label>StartTime</label>
			<?php #prefills field with collected StartTime ?>
			<input type="time" name="StartTime" value="<?php echo $StartTime;?>">
		</div>
		<div class="input-group">
			<label>Shoot Length (in hours)</label>
			<?php #prefills field with collected Length ?>
			<input type="integer" name="Length" value="<?php echo $Length;?>">
		</div>
        <div class="input-group">
			<label>Price</label>
			<?php #prefills field with collected Price ?>
			<input type="integer" name="Price" value="<?php echo $Price;?>">
		</div>
        <div class="input-group">
			<label>Status</label>
 			<select name="Status">
			 <?php #prefills field with collected Status ?>
            <option value= <?php echo $Status ?> > <?php echo $Status;?></option>
 			<option value="Confirmed">Confirmed</option>
  			<option value="Unconfirmed">Unconfirmed</option>
            <option value="Cancelled">Cancelled</option>
			</select>
		</div>
        <div class="input-group">
			<label>Paid</label>
 			<select name="Paid">
			 <?php #prefills field with collected Paid ?>
            <option value= <?php echo $Paid ?> > <?php echo $Paid;?></option>
 			<option value="yes">Yes</option>
  			<option value="no">No</option>
			</select>
		</div>
		<div class="input-group">
			<button class="btn" type="submit" name="update" style="background: #556B2F;display: block; margin-left: auto;
    		margin-right: auto; width: 5em" >update</button>
		</div>

	</form>
	<br><br><br>
	</body>



<?php
}
?>
</html>




