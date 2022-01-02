<html>
<?php
session_start();
include 'Enitity/connect.php';
$message="";
$review = false;
$viewReview = false;

#checks if the del class button (cancel) is selected
if (isset($_GET['del'])) {
		$id = $_GET['del'];
		$state = "Cancelled";
		#change booking status to cancelled in booking table
		mysqli_query($con, "UPDATE booking SET Status='$state' WHERE BookingID='".$id."'") or die (mysqli_error($con));
		$message = "Booking Cancelled"; 
}

if (isset($_GET['pay'])) {
	header("Location:payment.php");
}

if (isset($_GET['receipt'])) {
	$booking = $_GET['receipt'];
	include "Enitity/bookingPDF.php";
	$message = "Receipt Downloaded";
}

if (isset($_GET['see'])) {
	$booking = $_GET['see'];
	$selection = mysqli_query($con, "SELECT Rating,Review FROM booking WHERE BookingID='".$booking."'") or die(mysqli_error($con));
  	$check  = mysqli_fetch_array($selection);
	$viewReview = true;
}

if (isset($_GET['pay'])) {
	header("Location:payment.php");
}

if (isset($_GET['review'])) {
	$booking = $_GET['review'];
	$selection = mysqli_query($con, "SELECT * FROM booking WHERE BookingID='".$booking."'") or die(mysqli_error($con));
  	$check  = mysqli_fetch_array($selection);
	

	if($check['Rating'] != "0" && !empty($check['Rating']))
	{
		$message .= "This booking has already been reviewed <br>";
	}
	else
	{
		$review = true;
	}	
}

if(isset($_POST['Submit'])) {
	//initialising variable
    $message = "";
	$comment = $_POST['review'];
	$booking = $_POST['booking'];
	$rating = $_POST['rating'];

	$selection = mysqli_query($con, "SELECT * FROM booking WHERE BookingID='".$booking."'") or die(mysqli_error($con));
  	$check  = mysqli_fetch_array($selection);
	
	if(empty($booking) || empty($rating)){
		$message .= "Please fill in all of the fields <br>";
	}
	if($check['Paid'] == "no"){
		$message .= "You can't review a booking that hasn't been paid for <br>";
	}
	if($_SESSION['ID'] != $check['ConsumerID']){
		$message .= "You can't review another persons booking <br>";
	}
	if(strlen($comment)>5000)
	{
		$message .= "A review can have a maximum of up to 5000 characters <br>";
	}
	if(empty($message))
	{
		$result = serialize($comment);
		mysqli_query($con, "UPDATE booking SET Rating='$rating', Review='$result' WHERE BookingID='".$booking."'") or die (mysqli_error($con));
		header("location:booking.php");
	}
}
		
		
#redirect users that aren't logged in to the referal page
if(!isset($_SESSION["Username"]))
{ header("Location:Refer.php");}
?>

<head>
<meta charset="utf-8">
<title>Booking Page</title>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
<link rel="stylesheet" type = "text/css" href="CSS/table.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
		$select = mysqli_query($con, "SELECT * FROM shoottype WHERE Type LIKE '%".$_POST['search']."%'")
		or die(mysqli_error($con));
		$Shoot = mysqli_fetch_array($select);

		#select bookings related to search
        $query = mysqli_query($con, "SELECT * FROM booking INNER JOIN package ON booking.PackageID = package.PackageID WHERE Date LIKE'%".$_POST['search']."%' 
		or ShootLocation LIKE'%".$_POST['search']."%' or StartTime LIKE'%".$_POST['search']."%' or Price LIKE'%".$_POST['search']."%' or 
		Status LIKE'%".$_POST['search']."%' or ShootTypeID='".$Shoot['ShootTypeID']."' or Type LIKE '%".$_POST['search']."%' or 
		Paid LIKE'%".$_POST['search']."%'")or die(mysqli_error($con));
	}
	else {
		#select all bookings for that photographer
        $query = mysqli_query($con, "SELECT * FROM booking WHERE PhotographerID='". $_SESSION["ID"]."'")
   		or die(mysqli_error($con));
	}
}
   
#select from bookings for that consumer
if ($_SESSION["Type"] === "Consumer") {
	#select bookings from search for that consumer
    if (isset($_POST['_search'])) {
		#get ShootTypeID from search in ShootType table
		$select = mysqli_query($con, "SELECT * FROM shoottype WHERE Type LIKE '%".$_POST['search']."%'")
		or die(mysqli_error($con));
		$Shoot = mysqli_fetch_array($select);

		#select bookings related to search for that consumer
		$query = mysqli_query($con, "SELECT * FROM booking INNER JOIN package ON booking.PackageID = package.PackageID WHERE ConsumerID='". $_SESSION["ID"]."' 
		and (Date LIKE'%".$_POST['search']."%' or ShootLocation LIKE'%".$_POST['search']."%' or StartTime LIKE'%".$_POST['search']."%' or 
		Price LIKE'%".$_POST['search']."%' or Status LIKE'%".$_POST['search']."%' or ShootTypeID='".$Shoot['ShootTypeID']."' or 
		Type LIKE'%".$_POST['search']."%' or Paid LIKE'%".$_POST['search']."%')")or die(mysqli_error($con));
    } else {
		#select all bookings for that consumer
        $query = mysqli_query($con, "SELECT * FROM booking WHERE ConsumerID='". $_SESSION["ID"]."'")
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

<?php if(!empty($message)) { ?> <div class="message"> <?php echo $message; ?> </div> <?php } ?>

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
		$collect1 = mysqli_query($con, "SELECT * FROM shoottype WHERE ShootTypeID='".$row["ShootTypeID"]."'") or die(mysqli_error($con));
		$Shootings = mysqli_fetch_array($collect1);
		$collect2 = mysqli_query($con, "SELECT * FROM package WHERE PackageID='".$row['PackageID']."'") or die(mysqli_error($con));
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
			?>
			<td>
				<a href="booking.php?receipt=<?php echo $row['BookingID']; ?>" class="edit_btn"  style="background:#74ab0f">Receipt</a>
			</td>
			<?php
            if ($row['Status'] != "Cancelled") {
                if ($_SESSION["Type"] == "Consumer" && $row['Paid']=="yes" && $row['Rating'] != 0) {
                    ?>
				<td>
					<a href="booking.php?see=<?php echo $row['BookingID']; ?>" class="edit_btn"  style="background:#8e35b8">Reviews</a>
				</td>
				<?php
                } elseif ($_SESSION["Type"] == "Consumer" && $row['Paid']=="yes") {
                    ?>
				<td>
					<a href="booking.php?review=<?php echo $row['BookingID']; ?>" class="edit_btn"  style="background:#7542f5">Review</a>
				</td>
				<?php
                } else { ?>
				<td>
					<a href="booking.php?pay=<?php echo $row['BookingID']; ?>" class="edit_btn" style="background:#4287f5">Pay</a>
				</td>
				<?php } ?>
				<td>
					<a onclick="javascript:confirmationDelete($(this));return false;" href="booking.php?del=<?php echo $row['BookingID']; ?>" class="del_btn">Cancel</a>
				</td>
			<?php } ?>
		</tr>
	<?php }?>
</table>

<?php if($review){ ?>
	<h2 align="center">Review</h2>
	
    <form method="post" align="center">

    <div class="input-group">
    <label>Booking Reference</label> 
    <input name="booking" value="<?php echo $booking;?>"></input>
    </div>
	<br>

	<div class="input-group">
    <label>Rating</label> 
    <span class="star-rating">
	<input type="radio" name="rating" value="1"><i></i>
	<input type="radio" name="rating" value="2"><i></i>
	<input type="radio" name="rating" value="3"><i></i>
	<input type="radio" name="rating" value="4"><i></i>
	<input type="radio" name="rating" value="5"><i></i>
	</span>
	</div>
    <br><br>

    <div class="input-group">
    <label>Review</label>
    <textarea name="review"></textarea>
    </div>

    <button type="submit" class="btn" name="Submit">Send</button>
    </form>
<?php } 

elseif($viewReview) { ?>
<div align="center">
	<div class="review" align="center">
	<h2>Rating</h2><br>
	<?php for($i=0; $i<$check['Rating']; $i++){ ?> 
    <span class="fa fa-star checked"></span>
	<?php }
	for($i=0; $i<5-$check['Rating']; $i++){ ?> 
    <span class="fa fa-star"></span>
	<?php } ?>
	<br><br>
	<?php if(!empty(unserialize($check['Review']))){ ?>
	<h2>Review</h2><br>
	<?php echo '"'.unserialize($check['Review']).'"'; }?>
	</div>
</div>


<?php } ?>


<br><br><br>
</body>
</html>

<script>
function confirmationDelete(anchor)
{
   var conf = confirm('Are you sure want to cancel this booking?');
   if(conf)
      window.location=anchor.attr("href");
}
</script>




