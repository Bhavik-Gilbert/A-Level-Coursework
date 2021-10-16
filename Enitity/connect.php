<?php
$localhost = "localhost"; # your host name
$username = "root"; # your database name
$password = ""; # your database password
$dbname = "s1900395"; #

# connecting to datbase
$con = new mysqli($localhost, $username, $password, $dbname);

# checking connection - outputting if connection failed
if($con->connect_error) {
    die("connection failed : " . $con->connect_error);
}

?>