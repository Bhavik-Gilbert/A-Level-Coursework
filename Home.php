<DOCTYPE HTML>
<?php
session_start();
?>
<html>
<?php
#links the stylesheet to determine the design of the page
?>
<head>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
<link rel="stylesheet" type = "text/css" href="CSS/table.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Home Page</title>
</head>
<body>
<?php
#add the navbar under the header
include 'Enitity/menu.php';
?>


<br><br>
<div align="center">

<h1>Reviews</h1>


<?php
include 'Enitity/connect.php';

$states = array();
if (isset($_POST['_search'])) {
    $search = $rating = $year = "";
    if (!empty($_POST['search'])) {
        $search = "Review LIKE'%".$_POST['search']."%'";
        array_push($states,$search);
    }
    if (!empty($_POST['rating'])) {
        $rating = "Rating='".$_POST['rating']."'";
        array_push($states,$rating);
    }
    if (!empty($_POST['year'])) {
        $year = "YEAR(Date)='".$_POST['year']."'";
        array_push($states,$year);
    }
    if (!empty($states)) {
        $sql = "SELECT Rating,Review,Date FROM booking WHERE ";
        for($i=0;$i<count($states);$i++)
        {
            $sql .= $states[$i];
            if($i != count($states)-1)
            {
                $sql .= " AND ";
            }
        }
        $sql .= " ORDER BY date DESC";
        $query = mysqli_query($con, $sql)  or die(mysqli_error($con));
    }
}
if(empty($states)) {
    $query = mysqli_query($con, "SELECT Rating,Review,Date FROM booking ORDER BY date DESC") or die(mysqli_error($con));
}

$collectYears = mysqli_query($con, "SELECT YEAR(Date) FROM booking") or die(mysqli_error($con));

$list = array();
while ($check = mysqli_fetch_array($collectYears)) {
    $value = $check[0];
    if(!in_array($value, $list))
    {
        array_push($list, $value);
    }
}
sort($list);
?>

<form action="" method="post" align="center" style="background-color:transparent;
	border: solid transparent";>
<div class="input-group">
<input name="search" type="text" placeholder="Text Search" style="height: 30px;
    width: 100%; font-size: 16px;">
</div>

<div class="input-group">
<select name="rating" style="height: 30px; width: 100%; font-size: 16px;">
  <option value="">Rating</option>
  <option value="1">1 Star</option>
  <option value="2">2 Stars</option>
  <option value="3">3 Stars</option>
  <option value="4">4 Stars</option>
  <option value="5">5 Stars</option>
</select>
</div>

<div class="input-group">
<select name="year" style="height: 30px; width: 100%; font-size: 16px;">
  <option value="">Year</option>
  <?php for($i=0; $i<count($list);$i++){?>
        <option value="<?php echo $list[$i] ?>"><?php echo $list[$i] ?></option>
  <?php } ?>  
</select>
</div>

<div class="input-group">
<button class="btn" type="submit" name="_search" style="display: block; margin-left: auto;
    margin-right: auto; width: 8em">Search</button>
</div>
</form>

<?php
while ($row = mysqli_fetch_array($query)) {
    if($row['Rating'] != "0" && !empty($row['Rating']))
	{
    ?>
        <div class="review" align="center">
            <h2>Rating</h2><br>
            <?php echo $row['Date'] . "<br>";
            for($i=0; $i<$row['Rating']; $i++){ ?> 
                <span class="fa fa-star checked"></span>
            <?php }
            for($i=0; $i<5-$row['Rating']; $i++){ ?> 
                <span class="fa fa-star"></span>
            <?php } ?>
            <br><br>
            <?php if(!empty(unserialize($row['Review']))){ ?>
                <h2>Review</h2><br>
                <?php echo '"'.unserialize($row['Review']).'"'; 
            } ?>
	    </div>
<?php
	}
}
?>

<br><br><br>
<a class="btn" href="login.php" style="display:inline-block; overflow:auto; background:#05a688">Login</a>
<a class="btn" href="signup.php" style="display:inline-block; overflow:auto; background:#05a688">Signup</a>
<a class="btn" href="contact.php" style="display:inline-block; overflow:auto; background:#ad6505">Contact</a>
<a class="btn" href="Pricing.php" style="display:inline-block; overflow:auto; background:#3805a6">Pricing</a>
<a class="btn" href="Portfolio.php" style="display:inline-block; overflow:auto; background:#3805a6">Portfolio</a>

<br><br><br>
</div>
</body>
</html>
</DOCTYPE>
