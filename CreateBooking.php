<?php
session_start();
$message="";
#checks if form has been submitted
if(!empty($_POST)) {
  #connects page to database
  include 'Enitity/connect.php';

  #collects data from form
  $ShootType = $_POST['ShootType'];
  $PackageType = $_POST['PackageType'];
  $Date = $_POST['Date'];
  $Address = $_POST['Address'];
  $StartTime = $_POST['StartTime'];
  $Length = $_POST['Length'];
  $ID = $_SESSION['ID'];

  #stops data repetition in the database/double booking
  $Check2 = mysqli_query($con, "SELECT * FROM booking WHERE Date='".$Date."' and StartTime='".$StartTime."' and ShootLocation='".$Address."'") or die(mysqli_error($con));
  $BookingCheck  = mysqli_fetch_array($Check2);

  $Check1 = mysqli_query($con, "SELECT * FROM booking WHERE Date='".$Date."'") or die(mysqli_error($con));
  $TimeCheck  = mysqli_fetch_array($Check1);
	
  #validate all values in the form
  if (empty($ShootType) || empty($PackageType) || $empty($Date) || empty($Address) || empty($StartTime) || empty($Length)){
	  $message = "Please fill in all of the fields";
  }
  else if(is_array($BookingCheck)){
	  	$message= "This booking is already registered";
    }
  else if(is_array($TimeCheck)){
	  	$message= "This time slot is already taken";
    }
  else if (!is_numeric($Length))
    {$message = "Invalid Value for Shoot Length Field";
    }
  else if ($Length>12 || $Length<0)
	  {$message = "Length is too long";
    }
	
  else{
    #gets price for selected shoottype in the ShootType table
	  $select1 = mysqli_query($con, "SELECT * FROM shoottype WHERE ShootTypeID='".$ShootType."'")	or die(mysqli_error($con));
	  $Shoot = mysqli_fetch_array($select1);

    #gets price for selected package in the package table
    $select2 = mysqli_query($con, "SELECT * FROM package WHERE PackageID='".$PackageType."'") or die(mysqli_error($con));
    $Package = mysqli_fetch_array($select2);
  
    #calculates estimated price
    $Length = (float)$Length;
    $Add1 = (float)$Shoot["BasePrice"];
    $Add2 = (float)$Package["AddedCost"];
    $Price = (float)(($Add1*$Length) + $Add2);

    #assigns values for use in invoice page
    $_SESSION["PackageID"] = $PackageType;
    $_SESSION["ShootID"] = $ShootType;
    $_SESSION["Date"] = $Date;
    $_SESSION["Address"] = $Address;
    $_SESSION["StartTime"] = $StartTime;
    $_SESSION["Length"] = $Length;
    $_SESSION["Price"] = $Price;
	
    if ($_SESSION["Type"] === "Consumer") {
      #saves record to booking using SessionID as ConsumerID
      $sql = mysqli_query($con, "INSERT INTO booking (ConsumerID, PhotographerID , ShootTypeID , PackageID , Date , ShootLocation , StartTime, Length , Price , Status, Paid) 
      VALUES ('$ID','1','$ShootType','$PackageType','$Date','$Address','$StartTime','$Length','$Price','Unconfirmed', 'no')") or die (mysqli_error($con));
      }

    if ($_SESSION["Type"] === "Photographer"){
      #saves record to booking using specified photographer record in consumer table as ConsumerID
      $sql = mysqli_query($con, "INSERT INTO booking (ConsumerID, PhotographerID , ShootTypeID , PackageID , Date , ShootLocation , StartTime, Length , Price , Status, Paid) 
      VALUES ('38','1','$ShootType','$PackageType','$Date','$Address','$StartTime','$Length','$Price','Unconfirmed', 'no')") or die (mysqli_error($con));
      }

    $message =  "New record created successfully.";
    #Send user to invoice page
    header("Location:Invoice.php");
  }
}

#redirect users that aren't logged in to the referal page
if(!isset($_SESSION["Username"]))
{ header("Location:Refer.php");}
?>


<html>
<head>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
<link rel="stylesheet" type = "text/css" href="CSS/table.css">
<title>Create Booking Page</title>
</head>
<body>
<?php
#adds the navbar to the page
include 'Enitity/menu.php';
?>
</body>
<h1 style="text-align:center">New Booking</h1>
<?php #creates the form and displays error message?>
<form name="frmsign" method="post" action="" align="center">
<div class="message"><?php if($message!="") { echo $message; } ?></div>

<div class="input-group">
<label>Shoot Type</label>
 <select name="ShootType">
  <option value="">Select...</option>
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
  <option value="">Select...</option>
  <option value="1">Standard</option>
  <option value="2">Photo+</option>
  <option value="3">Photo Max</option>
  <option value="4">Studio</option>
</select>
</div>

<div class="input-group">
<label>Date</label>
<input type="date" name="Date">
</div>

<div class="input-group">
<label>Shoot Address</label>
<input type="text" name="Address">
</div>

<div class="input-group">
<label>Start Time</label>
<input type="time" name="StartTime">
</div>

<div class="input-group">
<label>Shoot Length (in hours)</label>
<input type="number" name="Length">
</div>

<div class="input-group" align="center">
<button class="btn" type="submit" name="Submit" >Submit</button>
</div>
</form>

<br><br><br>
</html>
</DOCTYPE>
