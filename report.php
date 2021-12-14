<html>
<?php

session_start();
#connects page to the database
include 'Enitity/connect.php';

#a function that selects what data to collect and gets the SUM and number or data entries
function report($switch, $month, $year)
{
    #connects function to the database
    include 'Enitity/connect.php';
    #selects bookings from its selected entry and gets the sum and number of entries
    if ($switch == "yes") {
        $query = mysqli_query($con, "SELECT COUNT(Price) FROM booking WHERE MONTH(DATE)='".$month."' and YEAR(DATE)='".$year."'") or die(mysqli_error($con));
        $query1 = mysqli_query($con, "SELECT SUM(Price) FROM booking WHERE MONTH(DATE)='".$month."' and YEAR(DATE)='".$year."'") or die(mysqli_error($con));
    } elseif ($switch == "no") {
        $query = mysqli_query($con, "SELECT COUNT(Price) FROM booking WHERE YEAR(DATE)='".$year."'") or die(mysqli_error($con));
        $query1 = mysqli_query($con, "SELECT SUM(Price) FROM booking WHERE YEAR(DATE)='".$year."'") or die(mysqli_error($con));
    } elseif ($switch == "Package") {
        $query = mysqli_query($con, "SELECT COUNT(Price) FROM booking WHERE YEAR(DATE)='".$year."' and PackageID='".$month."'") or die(mysqli_error($con));
        $query1 = mysqli_query($con, "SELECT SUM(Price) FROM booking WHERE YEAR(DATE)='".$year."' and PackageID='".$month."'") or die(mysqli_error($con));
    }
    elseif ($switch == "Shoot") {
        $query = mysqli_query($con, "SELECT COUNT(Price) FROM booking WHERE YEAR(DATE)='".$year."' and ShootTypeID='".$month."'") or die(mysqli_error($con));
        $query1 = mysqli_query($con, "SELECT SUM(Price) FROM booking WHERE YEAR(DATE)='".$year."' and ShootTypeID='".$month."'") or die(mysqli_error($con));
    }
    elseif($switch=="All"){
        $query = mysqli_query($con, "SELECT COUNT(Price) FROM booking") or die(mysqli_error($con));
        $query1 = mysqli_query($con, "SELECT SUM(Price) FROM booking") or die(mysqli_error($con));
    }
    #collects the SQL statements and ensures a 0 value
    $row = mysqli_fetch_array($query);
    $row1 = mysqli_fetch_array($query1);
    if($row1[0] == 0){$row1[0] = 0;}
#outputs the figures requested
?>
The number of orders you've had are <?php echo$row[0]; ?>
<br>
The revenue you made is £<?php echo$row1[0];
}
function reportP($year){
?> 
<div class="container6" align="center">
<h4>Corporate Headshots</h4>
<?php
report("Shoot",0,$year) ?>
<br>

<h4>Portrait Photography</h4>
<?php
report("Shoot",1,$year) ?>
<br>

<h4>Business Portraits</h4>
<?php
report("Shoot",2,$year) ?>
<br>
</div>

<div class="container6" align="center">
<h4>Corporate Lifestyle</h4>
<?php
report("Shoot",3,$year) ?>
<br>

<h4>Corporate Event Photography</h4>
<?php
report("Shoot",4,$year) ?>
<br>

<h4>Exhibition and Conference</h4>
<?php
report("Shoot",5,$year) ?>
<br>
</div>

<div class="container6" align="center">
<h4>Lifestyle Photography</h4>
<?php
report("Shoot",6,$year) ?>
<br>

<h4>Event Photography</h4>
<?php
report("Shoot",7,$year) ?>
<br>

<h4>Scientific Photography</h4>
<?php
report("Shoot",8,$year) ?>
<br>
</div> <?php }

#redirects users to referal page
if(!isset($_SESSION["Username"]))
{ header("Location:Refer.php");}

#redirect users that are consumers to the account page
if($_SESSION["Type"] == "Consumer")
{ header("Location:Account.php");}
?>

<head>
<meta charset="utf-8">
<title>Report Page</title>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
<link rel="stylesheet" type = "text/css" href="CSS/link.css">
<link rel="stylesheet" type = "text/css" href="CSS/table.css">
<link rel="stylesheet" type = "text/css" href="CSS/graph.css">
</head>
<?php #page specific style code ?>
<style>
    h3 {color: #FFF;}
    h4{color: #FFF;}
</style>

<body>
<?php
#displays the navbar at the top of the page
include 'Enitity/menu.php';
?>
<br>
<h1 style="text-align:center">Reports</h1>

<?php #creates a table and headings ?>
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
		</tr>
	</thead>
	
	<?php 
    #selects all records in the Booking Table and connects it to the ShootType Table
    $query = mysqli_query($con, "SELECT * FROM booking INNER JOIN shoottype ON booking.ShootTypeID = shoottype.ShootTypeID") or die(mysqli_error($con));
    #displays records in table
	while ($row = mysqli_fetch_array($query)) {
        #gets Package name at the corresponding PackageID in the Package Table
		$collect2 = mysqli_query($con, "SELECT * FROM Package WHERE PackageID='".$row['PackageID']."'") or die(mysqli_error($con));
		$Packagings = mysqli_fetch_array($collect2);
        #outputs records into table
		echo
		   "<tr>
		   <td>{$row['BookingID']}</td>
		   <td>{$row['Date']}</td>
		   <td>{$row['ShootLocation']}</td>
		   <td>{$row['StartTime']}</td>
		   <td>{$row['Length']}</td>
		   <td>{$row['Price']}</td>
		   <td>{$row['Type']}</td>
		   <td>{$Packagings['Type']}</td>
		   <td>{$row['Status']}</td>
		   <td>{$row['Paid']}</td>
   			";
			?>
		</tr>
	<?php }?>
</table>
<br>

<div align="center">
<div class="container7" align="center">
<h3>Total</h3>
<?php
report("All",5,$year)
?>
</div>
</div>

<br><br>

<div align = "center">
<?php
include 'Enitity/graph.php';
?>
</div>

<br><br>
<div align="center">
<div class="container1" align="center">
<h3>This Month</h3>
<?php 
report("yes",date("m"),date("Y"))
?>
</div>

<div class="container1" align="center">
<h3>Last Month</h3>
<?php
if(date("m") == 1){$month1 = 12; $year1 = date("Y") - 1;}
else{$month1 = date("m") - 1; $year1 = date("Y");}
report("yes",$month1,$year1)
?>
</div>

<div class="container1" align="center">
<h3>Last 3 Months</h3>
<?php
#used to get the sum of multiple months
if($month1 == 1){$month2 = 12; $year2 = $year1 - 1;}
else{$month2 = $month1 - 1; $year2 = $year1;}

#selects records for current month and gets the sum of the price and number of entries
$query1_1 = mysqli_query($con, "SELECT COUNT(PRICE) FROM booking WHERE MONTH(DATE)='".date("m")."' and YEAR(DATE)='".date("Y")."'") or die(mysqli_error($con));
$query1_2 = mysqli_query($con, "SELECT SUM(PRICE) FROM booking WHERE MONTH(DATE)='".date("m")."' and YEAR(DATE)='".date("Y")."'") or die(mysqli_error($con));
$row1_1 = mysqli_fetch_array($query1_1);
$row1_2 = mysqli_fetch_array($query1_2);
#selects records for last month and gets the sum of the price and number of entries
$query2_1 = mysqli_query($con, "SELECT COUNT(PRICE) FROM booking WHERE MONTH(DATE)='".$month1."' and YEAR(DATE)='".$year1."'") or die(mysqli_error($con));
$query2_2 = mysqli_query($con, "SELECT SUM(PRICE) FROM booking WHERE MONTH(DATE)='".$month1."' and YEAR(DATE)='".$year1."'") or die(mysqli_error($con));
$row2_1 = mysqli_fetch_array($query2_1);
$row2_2 = mysqli_fetch_array($query2_2);
#selects records from 2 months ago and gets the sum of the price and number of entries
$query3_1 = mysqli_query($con, "SELECT COUNT(PRICE) FROM booking WHERE MONTH(DATE)='".$month2."' and YEAR(DATE)='".$year2."'") or die(mysqli_error($con));
$query3_2 = mysqli_query($con, "SELECT SUM(PRICE) FROM booking WHERE MONTH(DATE)='".$month2."' and YEAR(DATE)='".$year2."'") or die(mysqli_error($con));
$row3_1 = mysqli_fetch_array($query3_1);
$row3_2 = mysqli_fetch_array($query3_2);

#calculates totals
$n = $row1_1[0] + $row2_1[0] + $row3_1[0];
$r = $row1_2[0] + $row2_2[0]  +$row3_2[0];
if($r == 0){$r=0;}

#displays the requested figures
?>
The number of orders you've had are <?php echo$n; ?>
<br>
The revenue you made is £<?php echo$r; ?>
</div>

<div class="container1" align="center">
<h3>This Year</h3>
<?php
report("no","",date("Y"))
?>
</div>

<div class="container1" align="center">
<h3>Last Year</h3>
<?php
$year1=date("Y") - 1;
report("no","",$year1)
?>
</div>
</div>

<br><br><br>
<div align = "center">

<div class="container4" align="center">
<h3>This Year</h3>
<br>
<h4>Standard</h4>
<?php
report("Package",1,date("Y")) ?>
<br>

<h4>Photo+</h4>
<?php
report("Package",2,date("Y")) ?>
<br>

<h4>Photo Max</h4>
<?php
report("Package",3,date("Y")) ?>

<h4>Studio</h4>
<?php
report("Package",4,date("Y")) ?>
</div>


<div class="container4" align="center">
<h3>Last Year</h3>
<br>
<h4>Standard</h4>
<?php
$LYear = date("Y") - 1;
report("Package",1,$LYear) ?>
<br>

<h4>Photo+</h4>
<?php
report("Package",2,$LYear) ?>
<br>

<h4>Photo Max</h4>
<?php
report("Package",3,$LYear) ?>
<br>

<h4>Studio</h4>
<?php
report("Package",4,$LYear) ?>
</div>
</div>


<br><br><br>
<div align = "center">
<div class="container5" align = "center">
<h3>This Year</h3>
</div>
<br>
<?php
reportP(date("Y")) ?>
</div>

<br><br>
<div align = "center">
<div class="container5" align = "center">
<h3>Last Year</h3>
</div>
<br>
<?php
reportP(date("Y")-1) ?>
</div>

<br><br><br>

<?php #creates a form to select time periods ?>
<div align="center">
<h2>Custom Compare</h2>
</div>
<form name="frmsign" method="post" action="" align="center">
<div class="input-group">
    Date 1
    <select name="Month1">
    <option value="">Select...</option>
    <option value="01">January</option>
    <option value="02">February</option>
    <option value="03">March</option>
    <option value="04">April</option>
    <option value="05">May</option>
    <option value="06">June</option>
    <option value="07">July</option>
    <option value="08">August</option>
    <option value="09">September</option>
    <option value="10">October</option>
    <option value="11">November</option>
    <option value="12">December</option>
    </select>
    <select name="Year1">
    <option value="">Select...</option>
    <option value="2020">2020</option>
    <option value="2021">2021</option>
    <option value="2022">2022</option>
    <option value="2023">2023</option>
    <option value="2024">2024</option>
    <option value="2025">2025</option>
    <option value="2026">2026</option>
    <option value="2027">2027</option>
    <option value="2028">2028</option>
    <option value="2029">2029</option>
    <option value="2030">2030</option>
    <option value="2031">2031</option>
    </select>
    </div>
    Date 2
    <select name="Month2">
    <option value="">Select...</option>
    <option value="01">January</option>
    <option value="02">February</option>
    <option value="03">March</option>
    <option value="04">April</option>
    <option value="05">May</option>
    <option value="06">June</option>
    <option value="07">July</option>
    <option value="08">August</option>
    <option value="09">September</option>
    <option value="10">October</option>
    <option value="11">November</option>
    <option value="12">December</option>
    </select>
    <select name="Year2">
    <option value="">Select...</option>
    <option value="2020">2020</option>
    <option value="2021">2021</option>
    <option value="2022">2022</option>
    <option value="2023">2023</option>
    <option value="2024">2024</option>
    <option value="2025">2025</option>
    <option value="2026">2026</option>
    <option value="2027">2027</option>
    <option value="2028">2028</option>
    <option value="2029">2029</option>
    <option value="2030">2030</option>
    <option value="2031">2031</option>
    </select>
    </div>
    <div class="input-group">
    <button class="btn" type="submit" name="view" style="background: #556B2F;display: block; margin-left: auto;
    margin-right: auto; width: 5em" >Send</button>
    </div>
</form>

<?php 
#checks if view is selected
if (isset($_POST['view'])) { ?>
    <div align="center">
    <?php
    #checks if a year is entered, only outputs if a year is entered
    if($_POST['Year1'] != ""){ ?>
        <div class = "container1" align="center"> 
        <h4>Date 1</h4><?php
        #checks if a month is entered
        if ($_POST['Month1'] != "") {
            report("yes", $_POST['Month1'], $_POST['Year1']);
        }   
        elseif($_POST['Year1'] != "") {
            report("no", $_POST['Month1'], $_POST['Year1']); 
        } ?> </div> <?php
    }
    #checks if a year is entered, only outputs if a year is entered
    if ($_POST['Year2'] != "") { ?>
        <div class = "container1" align="center">
        <h4>Date 2</h4> <?php
        #checks if a month is entered
        if ($_POST['Month2'] != "") {
            report("yes", $_POST['Month2'], $_POST['Year2']);
        } else{
            report("no", $_POST['Month2'], $_POST['Year2']);
        }
        ?> 
        </div> 
        <?php  }?>
    </div>
    <br><br><br>
</body>
<?php }
?>
</html>


