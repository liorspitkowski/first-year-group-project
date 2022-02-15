<?php

$host = "dbhost.cs.man.ac.uk";
$dbname = "2021_comp10120_x18";

//creates connection object to database
function connect(String $user, String $pass){

  global $host, $dbname;

  try
  {
    $conn = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    echo "Connected to $host successfully.";
    return $conn;
  }
  catch (PDOException $pe)
  {
    die("Could not connect to $host :" . $pe->getMessage());
  }
}

function createTables($conn){

  $sql = "CREATE TABLE user (
   userId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
   forename VARCHAR(30) NOT NULL,
   surname VARCHAR(30) NOT NULL,
   email VARCHAR(30) NOT NULL,
   password VARCHAR(128) NOT NULL)";

   $conn->query($sql);
}

function executeSQL($sql){

  $conn = connect("y66466tl", "SpagetiC0de");

}

$conn = connect("y66466tl", "SpagetiC0de");
createTables($conn);


 ?>