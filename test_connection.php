<?php
$database_host = "dbhost.cs.man.ac.uk";
$database_user = "a24872zh";
$database_pass = "12345x18";
$database_name = "a24872zh";

$conn = mysqli_connect($database_host, $database_user, $database_pass);

if (!$conn) {
  die("CONNECTION FAILED: " . mysqli_connect_error());
echo "All good";
}
?>
