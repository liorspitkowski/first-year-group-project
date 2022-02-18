<?php

$host = "dbhost.cs.man.ac.uk";
$dbname = "2021_comp10120_x18";
$user = "y66466tl";
$pass = "SpagetiC0de";
$conn;

//creates connection object to database
function connect(bool $debug){

  global $host, $dbname, $conn, $user, $pass;

  try
  {
    $conn = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);
    echo "Connected to $host successfully. \n";
    if ($debug){
      $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    }
    return $conn;
  }
  catch (PDOException $pe)
  {
    die("Could not connect to $host :" . $pe->getMessage());
  }
}

//creates connection using specified username and pass for debuging
function adminConnect(String $user, String $pass){

  global $host, $dbname, $conn;

  try
  {
    $conn = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    echo "Connected to $host successfully. \n";
    return $conn;
  }
  catch (PDOException $pe)
  {
    die("Could not connect to $host :" . $pe->getMessage());
  }
}

function SQLquery($sql){

  global $conn;

  try{
    $conn->query($sql);
    echo "SQL query ran successfully \n";
  }
  catch (PDOException $pe)
  {
    echo "ERROR : SQLquery '$sql' failed to run \n";
  }

}

// $sql = "CREATE TABLE ingredients (
// recipieId INT(6) UNSIGNED,
// foodId INT(6) UNSIGNED,
// amount DECIMAL(30),
// PRIMARY KEY (recipieId, foodId),
// FOREIGN KEY (recipieId) REFERENCES recipies(recipieId),
// FOREIGN KEY (foodId) REFERENCES foods(foodId))";
//
//
// connect(true);
// $conn->query($sql);
 ?>