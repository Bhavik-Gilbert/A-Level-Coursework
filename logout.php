<?php
session_start();
#destroys session by deleting all session details
session_destroy();
#redirects to login page
header("Location:login.php");
?>
