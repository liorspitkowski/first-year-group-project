<?php
$database_host = "dbhost.cs.man.ac.uk";
$database_user = "a24872zh";
$database_pass = "12345x18";
// $database_name = "a24872zh";
$group_dbnames = array(
    "2021_comp10120_x18",
);

echo ("Test 1");

// require_once('config.inc.php');

$conn = mysqli_connect($database_host, $database_user, $database_pass, $group_dbnames[0]);

echo ("Test 2");

if (!$conn) {
  die("CONNECTION FAILED: " . mysqli_connect_error());
echo ("All good");
}
?>
